<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

require '../db.php';
require_once("../logs/log_action.php");

/* ---------------- AUTH CHECK ---------------- */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    header("Location: Hostel/login.php");
    exit;
}

$role     = $_SESSION['user']['role'];
$hostel   = $_SESSION['user']['hostel'];
$username = $_SESSION['user']['username'];

/* ---------------- ROLE CHECK ---------------- */
if ($role === 'Hostel Guard') {
    die("Access denied: You are not allowed to delete students.");
}

/* ---------------- ID CHECK ---------------- */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request.");
}

try {
    $studentId = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    die("Invalid student ID.");
}

/* ---------------- FETCH STUDENT (HOSTEL ISOLATION) ---------------- */
$student = $db->student_data->findOne([
    '_id'    => $studentId,
    'hostel' => $hostel
]);

if (!$student) {
    die("Student not found or access denied.");
}

/* ---------------- ARCHIVE STUDENT ---------------- */
$deletedStudent = [
    'original_student_id' => (string)$student['_id'],
    'roll_no'             => $student['roll_no'],
    'name'                => $student['name'],
    'branch'              => $student['branch'],
    'degree'              => $student['degree'],
    'hostel'              => $student['hostel'],
    'room'                => $student['room'],
    'contact'             => $student['contact'],
    'email'               => $student['email'],
    'image_path'          => $student['image_path'] ?? null,

    'deleted_by'          => $username,
    'deleted_role'        => $role,
    'deleted_hostel'      => $hostel,
    'deleted_at'          => date("Y-m-d H:i:s")
];

/* ---------------- INSERT INTO deleted_students ---------------- */
$db->deleted_students->insertOne($deletedStudent);

/* ---------------- LOG DELETE ACTION (FIXED) ---------------- */
logAction(
    $username,
    $role,
    $hostel,
    "DELETE_STUDENT",
    "Deleted student {$student['roll_no']} ({$student['name']})"
);

/* ---------------- DELETE FROM student_data ---------------- */
$result = $db->student_data->deleteOne([
    '_id' => $studentId
]);

if ($result->getDeletedCount() !== 1) {
    die("Delete failed. Please try again.");
}

/* ---------------- REDIRECT ---------------- */
header("Location: Hostel/view_students.php");
exit;
