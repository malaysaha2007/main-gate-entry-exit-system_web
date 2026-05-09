<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require '../db.php';

/* ---------------- GET TOKEN ---------------- */
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No token received"
    ]);
    exit;
}

$token = $data['token'];

/* ---------------- VERIFY TOKEN (GOOGLE) ---------------- */
$google_api = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;

$response = file_get_contents($google_api);

if (!$response) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid token"
    ]);
    exit;
}

$user = json_decode($response, true);

/* ---------------- EXTRACT DATA ---------------- */
$email = $user['email'] ?? '';
$name  = $user['name'] ?? '';

/* ---------------- VALIDATE EMAIL DOMAIN ---------------- */
if (!str_ends_with(strtolower($email), "@iiitdmj.ac.in")) {
    echo json_encode([
        "status" => "error",
        "message" => "Use institute email only"
    ]);
    exit;
}

/* ---------------- EXTRACT ROLL ---------------- */
$roll = strtoupper(explode('@', $email)[0]);

if ($roll === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email format"
    ]);
    exit;
}

/* ---------------- CHECK IN students_excel ---------------- */
$student = $db->students_excel->findOne([
    "roll_no" => $roll
]);

if (!$student) {
    echo json_encode([
        "status" => "error",
        "message" => "You are not authorized. Contact hostel."
    ]);
    exit;
}

/* ---------------- CHECK REGISTRATION ---------------- */
$registered = $student['is_registered'] ?? false;

/* ---------------- CREATE SESSION ---------------- */
$_SESSION['student'] = [
    "roll_no" => $roll,
    "email" => $email,
    "name" => $name
];

/* ---------------- REDIRECT LOGIC ---------------- */
if ($registered) {

    // Already registered → go to dashboard
    echo json_encode([
        "status" => "success",
        "redirect" => "student_dashboard.php"
    ]);

} else {

    // Not registered → go to face registration
    echo json_encode([
        "status" => "success",
        "redirect" => "face_registration.php"
    ]);
}

exit;
