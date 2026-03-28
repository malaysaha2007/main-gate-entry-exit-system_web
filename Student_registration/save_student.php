<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

require_once("../db.php");
require_once("../logs/log_action.php");

/* -------------------------------------------------
   1. AUTH CHECK (SINGLE SOURCE OF TRUTH)
------------------------------------------------- */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    die("Unauthorized access");
}

$username = $_SESSION['user']['username'];   // hostel1_caretaker / warden
$role     = $_SESSION['user']['role'];       // Caretaker / Warden
$hostel   = $_SESSION['user']['hostel'];     // Hostel 1

/* Only Warden & Caretaker allowed */
if (!in_array($role, ['Warden', 'Caretaker'])) {
    die("Access denied");
}

/* -------------------------------------------------
   2. READ FORM DATA
------------------------------------------------- */
$roll      = trim($_POST['roll'] ?? '');
$name      = trim($_POST['name'] ?? '');
$branch    = trim($_POST['branch'] ?? '');
$degree    = trim($_POST['degree'] ?? '');
$room      = trim($_POST['room'] ?? '');
$studentNo = trim($_POST['studentNo'] ?? '');
$parentNo  = trim($_POST['parentNo'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = trim($_POST['password'] ?? '');

/* -------------------------------------------------
   3. VALIDATION
------------------------------------------------- */
if (
    $roll === "" || $name === "" || $branch === "" || $degree === "" ||
    $room === "" || $studentNo === "" || $parentNo === "" ||
    $email === "" || $password === ""
) {
    die("All fields are mandatory");
}

if ($studentNo === $parentNo) {
    die("Student and Parent contact numbers cannot be same");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters");
}

/* -------------------------------------------------
   4. DUPLICATE ROLL CHECK
------------------------------------------------- */
$students = $db->student_data;

if ($students->findOne(['roll_no' => $roll])) {
    die("Duplicate roll number");
}

/* -------------------------------------------------
   5. IMAGE CHECK
------------------------------------------------- */
if (
    !isset($_FILES['p1'], $_FILES['p2'], $_FILES['p3']) ||
    !is_uploaded_file($_FILES['p1']['tmp_name']) ||
    !is_uploaded_file($_FILES['p2']['tmp_name']) ||
    !is_uploaded_file($_FILES['p3']['tmp_name'])
) {
    die("All 3 photos are required");
}

/* -------------------------------------------------
   6. SAVE IMAGES
------------------------------------------------- */
$faceDir = "../face_data/$roll/";

if (!is_dir($faceDir)) {
    mkdir($faceDir, 0777, true);
}

move_uploaded_file($_FILES['p1']['tmp_name'], $faceDir . "1.jpeg");
move_uploaded_file($_FILES['p2']['tmp_name'], $faceDir . "2.jpeg");
move_uploaded_file($_FILES['p3']['tmp_name'], $faceDir . "3.jpeg");

/* -------------------------------------------------
   7. INSERT STUDENT (ALWAYS EXECUTES)
------------------------------------------------- */
$students->insertOne([
    "roll_no"    => $roll,
    "name"       => $name,
    "branch"     => $branch,
    "degree"     => $degree,
    "hostel"     => $hostel,   // 🔐 FROM SESSION ONLY
    "room"       => $room,
    "contact"    => [
        "student" => $studentNo,
        "parent"  => $parentNo
    ],
    "email"      => $email,
    "password"   => $password,   // plain text (as you want)
    "image_path" => "face_data/$roll/",
    "created_at" => date("Y-m-d H:i:s")
]);

/* -------------------------------------------------
   8. LOG — SINGLE, CORRECT
------------------------------------------------- */
logAction(
    $username,
    $role,
    $hostel,
    "REGISTER_STUDENT",
    "Registered student $roll ($name)"
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Registration Successful</title>
<style>
body{
  background:linear-gradient(135deg,#020617,#020b2d,#020617);
  min-height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  font-family:Segoe UI;
  color:#e5e7eb;
}
.card{
  background:#0f172a;
  padding:40px;
  border-radius:18px;
  max-width:520px;
  text-align:center;
}
.check{font-size:64px;color:#22c55e;}
.details{
  background:#020617;
  padding:16px;
  border-radius:12px;
  margin-top:20px;
  text-align:left;
}
a{
  display:inline-block;
  margin:12px;
  padding:12px 22px;
  border-radius:10px;
  text-decoration:none;
}
.register{background:#2563eb;color:white;}
.dashboard{background:#334155;color:white;}
</style>
</head>
<body>

<div class="card">
  <div class="check">✔</div>
  <h2>Student Registered Successfully</h2>

  <div class="details">
    <p><b>Name:</b> <?= htmlspecialchars($name) ?></p>
    <p><b>Roll:</b> <?= htmlspecialchars($roll) ?></p>
    <p><b>Branch:</b> <?= htmlspecialchars($branch) ?></p>
    <p><b>Degree:</b> <?= htmlspecialchars($degree) ?></p>
    <p><b>Hostel:</b> <?= htmlspecialchars($hostel) ?></p>
    <p><b>Room:</b> <?= htmlspecialchars($room) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($email) ?></p>
  </div>

  <a href="student_registration.php" class="register">Register Another</a>
  <a href="../Hostel/dashboard.php" class="dashboard">Dashboard</a>
</div>

</body>
</html>
