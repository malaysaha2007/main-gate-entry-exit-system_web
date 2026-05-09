<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require '../db.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/* ---------------- AUTH CHECK ---------------- */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    echo json_encode([
        "success_count" => 0,
        "duplicate_count" => 0,
        "error_count" => 1,
        "errors" => [["row" => "-", "roll" => "-", "issue" => "Unauthorized"]],
        "duplicates" => []
    ]);
    exit;
}

$hostel = $_SESSION['user']['hostel'];

/* ---------------- CHECK FILE ---------------- */
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
    echo json_encode([
        "success_count" => 0,
        "duplicate_count" => 0,
        "error_count" => 1,
        "errors" => [["row" => "-", "roll" => "-", "issue" => "File upload failed"]],
        "duplicates" => []
    ]);
    exit;
}

$file = $_FILES['file']['tmp_name'];

/* ---------------- LOAD EXCEL ---------------- */
try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
} catch (Exception $e) {
    echo json_encode([
        "success_count" => 0,
        "duplicate_count" => 0,
        "error_count" => 1,
        "errors" => [["row" => "-", "roll" => "-", "issue" => "Invalid Excel file"]],
        "duplicates" => []
    ]);
    exit;
}

/* ---------------- INIT ---------------- */
$success_count = 0;
$duplicate_count = 0;
$error_count = 0;

$errors = [];
$duplicates = [];

/* ---------------- BRANCH MAP ---------------- */
$branch_map = [
    "BCS" => "CSE",
    "BME" => "ME",
    "BEC" => "ECE",
    "BSM" => "SM",
    "BDS" => "Design"
];

/* ---------------- LOOP ---------------- */
$highestRow = $sheet->getHighestRow();

for ($i = 2; $i <= $highestRow; $i++) {

    // 🔴 Skip hidden rows
    if ($sheet->getRowDimension($i)->getVisible() === false) {
        continue;
    }

    $email = trim($sheet->getCell("A$i")->getValue());
    $name  = trim($sheet->getCell("B$i")->getValue());
    $room  = trim($sheet->getCell("C$i")->getValue());

    // 🔴 Skip empty rows
    if ($email === '' && $name === '' && $room === '') {
        continue;
    }

    $issues = [];

    if ($email === '') $issues[] = "Email missing";
    if ($name === '') $issues[] = "Name missing";

    if ($email !== '' && strpos(strtolower($email), "@iiitdmj.ac.in") === false) {
        $issues[] = "Invalid email domain";
    }

    $roll = '';
    if ($email !== '') {
        $roll = strtoupper(explode('@', $email)[0]);
    }

    if ($roll === '') $issues[] = "Invalid roll extraction";

    $branch = "UNKNOWN";
    if (strlen($roll) >= 5) {
        $branch_code = substr($roll, 2, 3);
        if (isset($branch_map[$branch_code])) {
            $branch = $branch_map[$branch_code];
        } else {
            $issues[] = "Unknown branch code";
        }
    } else {
        $issues[] = "Invalid roll format";
    }

    if (!empty($issues)) {
        $error_count++;
        $errors[] = [
            "row" => $i,
            "roll" => $roll,
            "issue" => implode(", ", $issues)
        ];
        continue;
    }

    try {
        $existing = $db->students_excel->findOne([
            "roll_no" => $roll
        ]);

        if ($existing) {
            $duplicate_count++;
            $duplicates[] = $roll;
            continue;
        }

        $db->students_excel->insertOne([
            "roll_no" => $roll,
            "name" => $name,
            "branch" => $branch,
            "hostel" => $hostel,
            "room" => $room,
            "contact" => [
                "email" => strtolower($email)
            ],
            "registration_type" => "import",
            "is_registered" => false,
            "created_at" => date("Y-m-d H:i:s")
        ]);

        $success_count++;

    } catch (Exception $e) {
        $error_count++;
        $errors[] = [
            "row" => $i,
            "roll" => $roll,
            "issue" => "Database error"
        ];
    }
}

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "success_count" => $success_count,
    "duplicate_count" => $duplicate_count,
    "error_count" => $error_count,
    "errors" => $errors,
    "duplicates" => $duplicates
]);

exit;
