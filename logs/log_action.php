<?php
require_once("../db.php");

date_default_timezone_set("Asia/Kolkata");

/**
 * Log user activity (single source of truth)
 */
function logAction(
    string $userId,
    string $role,
    string $hostel,
    string $actionType,
    string $description
) {
    global $db;

    $db->activity_logs->insertOne([
        "user_id"     => $userId,        // hostel1_caretaker
        "role"        => $role,          // Caretaker
        "hostel"      => $hostel,        // Hostel 1
        "action_type" => $actionType,    // REGISTER_STUDENT
        "description" => $description,   // Registered student 24BCS156 (Malay Saha)
        "timestamp"   => date("Y-m-d H:i:s")
    ]);
}
