<?php
header("Content-Type: application/json");
require "db.php";

date_default_timezone_set("Asia/Kolkata");

$today = date("Y-m-d");
$now   = date("Y-m-d H:i:s");

/* ---------------- VALIDATION ---------------- */
if (!isset($_POST['roll'], $_POST['purpose'])) {
    echo json_encode(["status"=>"error","message"=>"Missing data"]);
    exit;
}

$roll    = $_POST['roll'];
$purpose = $_POST['purpose'];

/* ---------------- FETCH STUDENT ---------------- */
$student = $db->students->findOne(["roll_no" => $roll]);

if (!$student) {
    echo json_encode(["status"=>"error","message"=>"Student not found"]);
    exit;
}

/* ---------------- LAST LOG ---------------- */
$lastLog = $db->gate_logs->findOne(
    ["roll_no" => $roll],
    ["sort" => ["_id" => -1]]
);

/* 🔒 DUPLICATE SCAN PROTECTION */
if ($lastLog) {
    $lastTime = $lastLog['exitTime'] ?? $lastLog['entryTime'];
    if ($lastTime && (strtotime($now) - strtotime($lastTime)) < 5) {
        echo json_encode([
            "status"  => "blocked",
            "message" => "Duplicate scan detected"
        ]);
        exit;
    }
}

$action = "EXIT";

/* ---------------- VACATION LOGIC ---------------- */
if ($purpose === "Vacation") {

    if ($lastLog && isset($lastLog['exitTime'])) {
        $lastDate = substr($lastLog['exitTime'], 0, 10);

        if ($lastDate === $today) {
            echo json_encode([
                "status"  => "blocked",
                "message" => "Vacation students cannot re-enter same day"
            ]);
            exit;
        }
    }

    // next day ENTRY allowed
    if ($lastLog && isset($lastLog['exitTime']) && substr($lastLog['exitTime'],0,10) !== $today) {
        $action = "ENTRY";
    } else {
        $action = "EXIT";
    }

}
/* ---------------- NORMAL PURPOSE ---------------- */
else {

    if ($lastLog) {
        $lastDate = substr(
            $lastLog['exitTime'] ?? $lastLog['entryTime'],
            0, 10
        );

        if ($lastDate === $today) {
            $action = ($lastLog['action'] === "EXIT") ? "ENTRY" : "EXIT";
        } else {
            $action = "EXIT";
        }
    }
}

/* ---------------- BUILD LOG (MATCH DB STRUCTURE) ---------------- */
$log = [
    "name"      => $student['name'],
    "roll_no"   => $roll,
    "hostel"    => $student['hostel'],
    "phone"     => $student['phone'],   // ✅ FIXED
    "purpose"   => $purpose,
    "action"    => $action,
    "source"    => "MAIN_GATE"
];

/* ---------------- SAVE ENTRY / EXIT ---------------- */
try {

    if ($action === "EXIT") {
        $log["exitTime"] = $now;

        $db->students->updateOne(
            ["roll_no" => $roll],
            ['$set' => [
                "status" => "OUT",
                "lastExitTime" => $now
            ]]
        );

    } else {
        $log["entryTime"] = $now;

        $db->students->updateOne(
            ["roll_no" => $roll],
            ['$set' => [
                "status" => "IN",
                "lastEntryTime" => $now
            ]]
        );
    }

    $db->gate_logs->insertOne($log);

    echo json_encode([
        "status" => "success",
        "action" => $action
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status"  => "error",
        "message" => "Database error",
        "debug"   => $e->getMessage()
    ]);
}
