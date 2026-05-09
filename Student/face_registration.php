<?php
session_start();
require '../db.php';

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit;
}

$roll = $_SESSION['student']['roll_no'];

/* ================= FETCH FROM students_excel ================= */
$student = $db->students_excel->findOne([
    "roll_no" => $roll
]);

if (!$student) {
    die("Unauthorized access");
}

/* ================= AUTO DATA ================= */
$name   = $student['name'] ?? '';
$branch = $student['branch'] ?? '';
$hostel = $student['hostel'] ?? '';
$room   = $student['room'] ?? '';
$email  = $student['contact']['email'] ?? '';
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Registration | PDPM IIITDMJ</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root {
  --bg-main:#071126;
  --text-muted:#9aa8c7;
}

*{box-sizing:border-box;margin:0;padding:0;font-family:"Segoe UI",sans-serif;}

body{
  min-height:100vh;
  background:linear-gradient(135deg,#020617,#020b2d,#020617);
  color:#e5e7eb;
}

/* ===== NAVBAR ===== */
.navbar-wrap {
  width:100%;
  background:#081126;
  border-bottom:1px solid rgba(255,255,255,0.08);
}

.navbar {
  display:flex;
  align-items:center;
  padding:12px 24px;
}

.brand {
  display:flex;
  align-items:center;
  gap:12px;
}

.brand-logo img {
  width:50px;
  border-radius:8px;
}

.brand-title {
  font-size:18px;
  font-weight:700;
  color:#3b82f6;
}

.brand-subtitle {
  font-size:12px;
  color:var(--text-muted);
}

.nav-center {
  flex:1;
  display:flex;
  justify-content:center;
}

.nav-links {
  display:flex;
  align-items:center;
  gap:10px;
}

.nav-links a {
  display:flex;
  align-items:center;
  height:38px;
  padding:0 14px;
  text-decoration:none;
  color:#e2e8f0;
  background:#10213f;
  border-radius:10px;
  font-size:14px;
}

/* ===== PAGE ===== */
.container{max-width:1000px;margin:40px auto;padding:10px;}
h1{text-align:center;}
.subtitle{text-align:center;color:#9ca3af;margin-bottom:30px;}

.card{
  background:rgba(15,23,42,.9);
  border-radius:14px;
  padding:30px;
}

.form-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:20px;
}

input,select{
  width:100%;padding:12px;border-radius:8px;
  background:#020617;border:1px solid #1e293b;color:white;
}

input[readonly]{
  opacity:0.7;
}

/* Upload */
.upload-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;margin-top:25px;
}

.upload-box{
  border:2px dashed #334155;
  padding:20px;
  text-align:center;
  border-radius:10px;
}

/* Buttons */
.buttons{
  margin-top:30px;
  display:flex;
  justify-content:center;
  gap:20px;
}

button{
  padding:12px 30px;
  border:none;
  border-radius:10px;
  cursor:pointer;
}

.submit{background:#2563eb;color:white;}
.cancel{background:#334155;color:white;}
</style>

</head>

<body>

<div class="container">

<h1>Student Registration</h1>
<div class="subtitle">Complete your profile</div>

<div class="card">

<form action="save_face_and_register.php" method="POST" enctype="multipart/form-data">

<div class="form-grid">

<input name="name" value="<?= htmlspecialchars($name) ?>" readonly>
<input name="roll" value="<?= htmlspecialchars($roll) ?>" readonly>
<input name="branch" value="<?= htmlspecialchars($branch) ?>" readonly>

<select name="degree" readonly>
  <option>B.Tech</option>
</select>

<input name="hostel" value="<?= htmlspecialchars($hostel) ?>" readonly>
<input name="room" value="<?= htmlspecialchars($room) ?>" readonly>

<input name="studentNo" placeholder="Student Contact No" required>
<input name="parentNo" placeholder="Parent Contact No" required>

<input name="email" value="<?= htmlspecialchars($email) ?>" readonly>
<input name="password" type="password" placeholder="Create Password" required>

</div>

<div class="upload-grid">
  <div class="upload-box">Photo 1<input type="file" name="p1" required></div>
  <div class="upload-box">Photo 2<input type="file" name="p2" required></div>
  <div class="upload-box">Photo 3<input type="file" name="p3" required></div>
</div>

<div class="buttons">
  <button type="submit" class="submit">Register</button>
  <button type="reset" class="cancel">Cancel</button>
</div>

</form>

</div>
</div>

</body>
</html>
