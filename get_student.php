<?php
header("Content-Type: application/json");
require "db.php";

if (!isset($_GET['roll'])) {
    echo json_encode(["error" => "Roll not provided"]);
    exit;
}

$roll = $_GET['roll'];

$student = $db->students->findOne(["roll_no" => $roll]);

if (!$student) {
    echo json_encode(["error" => "Student not found"]);
    exit;
}

echo json_encode([
    "name"          => $student["name"],
    "roll"          => $student["roll_no"],
    "hostel"        => $student["hostel"],
    "room"          => $student["room"],
    "phone"         => $student["contact"],
    "status"        => $student["status"],
    "email"         => $student["email"],
    "lastEntryTime" => $student["lastEntryTime"] ?? null,
    "lateEntry"     => $student["lateEntry"] ?? false
]);
