<?php
session_start();
require '../db.php';

/* ---------------- AUTH CHECK ---------------- */

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    header("Location: Hostel/login.php");
    exit;
}

$role   = $_SESSION['user']['role'];
$hostel = $_SESSION['user']['hostel'];

/* ---------------- ROLE CHECK ---------------- */

if ($role === 'Hostel Guard') {
    die("Access denied: You are not allowed to add students.");
}

$error = "";
$success = "";

/* ---------------- FORM SUBMISSION ---------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roll_no    = trim($_POST['roll_no']);
    $name       = trim($_POST['name']);
    $room_no    = trim($_POST['room_no']);
    $phone      = trim($_POST['phone']);

    if (
        $student_id === "" ||
        $roll_no === "" ||
        $name === "" ||
        $room_no === "" ||
        $phone === ""
    ) {
        $error = "All fields are required.";
    } else {

        /* Prevent duplicate roll number */
        $existing = $db->students->findOne([
            'roll_no' => $roll_no
        ]);

        if ($existing) {
            $error = "Student with this Roll No already exists.";
        } else {

            $db->students->insertOne([
                'student_id' => $student_id,
                'roll_no'    => $roll_no,
                'name'       => $name,
                'hostel'     => $hostel,     // AUTO from session
                'room_no'    => $room_no,
                'phone'      => $phone,
                'status'     => 'ACTIVE',
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            header("Location: Hostel/view_students.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Student</title>

<style>
body {
  font-family: system-ui, Arial, sans-serif;
  background: #071126;
  color: #e6eef8;
  padding: 40px;
}
.card {
  max-width: 600px;
  margin: auto;
  background: #27242c;
  padding: 24px;
  border-radius: 12px;
}
label {
  display: block;
  margin-top: 12px;
}
input {
  width: 100%;
  padding: 10px;
  margin-top: 6px;
  background: #0b1b33;
  border: 1px solid #444;
  color: #fff;
}
button {
  margin-top: 20px;
  padding: 10px 16px;
  background: #06b6d4;
  border: none;
  font-weight: bold;
  cursor: pointer;
}
.error {
  background: rgba(239,68,68,0.15);
  color: #f87171;
  padding: 10px;
  margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="card">
  <h2>Add Student (<?php echo htmlspecialchars($hostel); ?>)</h2>

  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post">


    <label>Roll Number</label>
    <input type="text" name="roll_no">

    <label>Student Name</label>
    <input type="text" name="name">

    <label>Room Number</label>
    <input type="text" name="room_no">

    <label>Phone Number</label>
    <input type="text" name="phone">

    <button type="submit">Add Student</button>

  </form>
</div>

</body>
</html>
