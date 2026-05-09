<?php
session_start();
require '../db.php';
require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

/* ================= CLOUDINARY CONFIG ================= */
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dbrjgcpkz',
        'api_key'    => '179582722719283',
        'api_secret' => 'hjyQQlHCtNXjO-kaXAfaE5XSD_I'
    ],
    'url' => ['secure' => true]
]);

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['student'])) {
    die("Unauthorized");
}

$roll = $_SESSION['student']['roll_no'];

/* ================= FETCH FROM students_excel ================= */
$student = $db->students_excel->findOne([
    "roll_no" => $roll
]);

if (!$student) {
    die("Unauthorized access");
}

/* ================= GET FORM DATA ================= */
$name       = $_POST['name'] ?? '';
$branch     = $_POST['branch'] ?? '';
$hostel     = $_POST['hostel'] ?? '';
$room       = $_POST['room'] ?? '';
$email      = $_POST['email'] ?? '';
$password   = $_POST['password'] ?? '';
$studentNo  = $_POST['studentNo'] ?? '';
$parentNo   = $_POST['parentNo'] ?? '';

if ($password === '' || $studentNo === '' || $parentNo === '') {
    die("All fields are required");
}

/* ================= HASH PASSWORD ================= */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* ================= UPLOAD PHOTOS TO CLOUDINARY ================= */
$photos = ['p1', 'p2', 'p3'];
$imageUrls = [];

foreach ($photos as $index => $p) {

    if (!isset($_FILES[$p]) || $_FILES[$p]['error'] !== 0) {
        die("Photo upload failed");
    }

    $tmp = $_FILES[$p]['tmp_name'];

    // Upload to Cloudinary
    $upload = $cloudinary->uploadApi()->upload($tmp, [
        'folder' => "students/$hostel/$roll",
        'public_id' => "img" . ($index + 1)
    ]);

    $imageUrls[] = $upload['secure_url'];
}

/* ================= GENERATE FACE EMBEDDING ================= */
$imageUrlForEmbedding = $imageUrls[0]; // use first image

curl_init("https://prbackend-production-b19a.up.railway.app/generate-embedding");

$data = json_encode([
    "image_url" => $imageUrlForEmbedding
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);

if ($response === false) {
    die("Error connecting to face recognition server");
}

curl_close($ch);

$result = json_decode($response, true);



$embedding = $result['embedding'];

/* ================= INSERT INTO student_auth_data ================= */
$db->student_auth_data->insertOne([
    "roll_no" => $roll,
    "name" => $name,
    "branch" => $branch,
    "hostel" => $hostel,
    "room" => $room,
    "contact" => [
        "email" => strtolower($email),
        "student_no" => $studentNo,
        "parent_no" => $parentNo
    ],
    "password" => $hashedPassword,
"face_images" => $imageUrls,
"face_embedding" => $embedding,
    "created_at" => date("Y-m-d H:i:s")
]);

/* ================= DELETE FROM students_excel ================= */
$db->students_excel->deleteOne([
    "roll_no" => $roll
]);

/* ================= SESSION UPDATE ================= */
$_SESSION['student']['is_registered'] = true;

/* ================= REDIRECT ================= */
header("Location: student_profile.php");
exit;