<?php
session_start();
require_once("../db.php");

/* ---------- AUTH CHECK ---------- */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    die("Access denied");
}

/* ---------- STUDENT FETCH ---------- */
if (!isset($_GET['id'])) {
    die("Invalid request");
}

try {
    $studentId = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    die("Invalid student ID");
}

$student = $db->student_data->findOne([
    "_id" => $studentId
]);

if (!$student) {
    die("Student not found");
}

/* ---------- SAFE VALUES ---------- */
$name   = $student["name"] ?? "";
$roll   = $student["roll_no"] ?? "";   // ✅ FIX IS HERE
$branch = $student["branch"] ?? "";
$degree = $student["degree"] ?? "";
$room   = $student["room"] ?? "";
$email  = $student["email"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Successful</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  min-height:100vh;
  background:linear-gradient(135deg,#020617,#020b2d,#020617);
  display:flex;
  justify-content:center;
  align-items:center;
  font-family:Segoe UI,Arial,sans-serif;
  color:#e5e7eb;
}

.card{
  background:rgba(15,23,42,0.95);
  width:520px;
  padding:40px;
  border-radius:18px;
  box-shadow:0 30px 70px rgba(0,0,0,.65);
  text-align:center;
}

.check{
  font-size:70px;
  color:#22c55e;
  margin-bottom:12px;
}

h2{
  margin:10px 0 24px;
}

.details{
  background:#020617;
  padding:18px;
  border-radius:12px;
  text-align:left;
  font-size:14px;
}

.details p{
  margin:8px 0;
}

.buttons{
  margin-top:28px;
  display:flex;
  justify-content:center;
  gap:16px;
}

a{
  text-decoration:none;
  padding:12px 24px;
  border-radius:10px;
  font-size:15px;
}

.back{
  background:#2563eb;
  color:white;
}

.dashboard{
  background:#334155;
  color:white;
}
</style>
</head>

<body>

<div class="card">
  <div class="check">✔</div>
  <h2>Student Updated Successfully</h2>

  <div class="details">
    <p><b>Name:</b> <?= htmlspecialchars($name) ?></p>
    <p><b>Roll No:</b> <?= htmlspecialchars($roll) ?></p>
    <p><b>Branch:</b> <?= htmlspecialchars($branch) ?></p>
    <p><b>Degree:</b> <?= htmlspecialchars($degree) ?></p>
    <p><b>Room:</b> <?= htmlspecialchars($room) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($email) ?></p>
  </div>

  <div class="buttons">
    <a href="../Hostel\view_students.php" class="back">Back to Students</a>
    <a href="../Hostel/dashboard.php" class="dashboard">Dashboard</a>
  </div>
</div>

</body>
</html>
