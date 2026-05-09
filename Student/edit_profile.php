<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

require_once("../db.php");
require_once("../logs/log_action.php");

/* ================= AUTH CHECK ================= */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    header("Location: Hostel/login.php");
    exit;
}

$staffRole   = $_SESSION['user']['role'];
$staffHostel = $_SESSION['user']['hostel'];
$staffUser   = $_SESSION['user']['username'];

/* Guards cannot edit */
if ($staffRole === "Hostel Guard") {
    die("Access denied");
}

/* ================= STUDENT ID ================= */
if (!isset($_GET['id'])) {
    die("Invalid request");
}

try {
    $studentId = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    die("Invalid student ID");
}

/* ================= FETCH STUDENT ================= */
$student = $db->student_data->findOne([
    "_id"    => $studentId,
    "hostel" => $staffHostel   // 🔐 hostel isolation
]);

if (!$student) {
    die("Student not found or access denied");
}

$error = "";

/* ================= UPDATE LOGIC ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name   = trim($_POST["name"] ?? "");
    $branch = trim($_POST["branch"] ?? "");
    $degree = trim($_POST["degree"] ?? "");
    $roll   = trim($_POST["roll_no"] ?? "");
    $room   = trim($_POST["room"] ?? "");
    $sPhone = trim($_POST["student_contact"] ?? "");
    $pPhone = trim($_POST["parent_contact"] ?? "");
    $email  = trim($_POST["email"] ?? "");

    if (
        $name === "" || $branch === "" || $degree === "" ||
        $roll === "" || $room === "" ||
        $sPhone === "" || $pPhone === "" || $email === ""
    ) {
        $error = "All fields are required.";
    } else {

        /* Prevent duplicate roll number */
        if ($roll !== $student["roll_no"]) {
            $exists = $db->student_data->findOne(["roll_no" => $roll]);
            if ($exists) {
                $error = "Roll number already exists.";
            }
        }

        if ($error === "") {

            $currentTime = date("Y-m-d H:i:s");

            /* ---------- UPDATE STUDENT ---------- */
            $db->student_data->updateOne(
                ['_id' => $student['_id']],
                [
                    '$set' => [
                        'name'              => $name,
                        'branch'            => $branch,
                        'degree'            => $degree,
                        'room'              => $room,
                        'roll_no'           => $roll,
                        'contact.student'   => $sPhone,
                        'contact.parent'    => $pPhone,
                        'email'             => $email,
                        'updated_at'        => $currentTime
                    ]
                ]
            );

            /* ---------- LOG ACTION (FIXED) ---------- */
            logAction(
                $staffUser,                 // hostel1_caretaker / hostel1_warden
                $staffRole,                 // Caretaker / Warden
                $staffHostel,               // Hostel 1
                "UPDATE_STUDENT",
                "Updated student {$roll} ({$name})"
            );

            /* ---------- REDIRECT ---------- */
            header("Location: edit_success.php?id=" . $student["_id"]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
  background: #071126;
  color: #e6eef8;
  font-family: Segoe UI, Arial, sans-serif;
}
.card {
  max-width: 760px;
  margin: 50px auto;
  background: #27242c;
  padding: 30px;
  border-radius: 14px;
}
h1 { color: #06b6d4; }
label {
  display: block;
  margin: 12px 0 6px;
  color: #98a6bf;
}
input, select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: none;
  background: #0b1b33;
  color: white;
}
button {
  margin-top: 20px;
  padding: 12px 22px;
  border: none;
  border-radius: 10px;
  background: #3b82f6;
  color: white;
  cursor: pointer;
}
.error {
  background: rgba(239,68,68,.15);
  color: #f87171;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
}
</style>
</head>

<body>
<div class="card">
  <h1>Edit Student</h1>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">

    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($student["name"]) ?>">

    <label>Branch</label>
    <input name="branch" value="<?= htmlspecialchars($student["branch"]) ?>">

    <label>Degree</label>
    <select name="degree">
      <option <?= $student["degree"] === "B.Tech" ? "selected" : "" ?>>B.Tech</option>
      <option <?= $student["degree"] === "M.Tech" ? "selected" : "" ?>>M.Tech</option>
      <option <?= $student["degree"] === "PhD" ? "selected" : "" ?>>PhD</option>
    </select>

    <label>Roll No</label>
    <input name="roll_no" value="<?= htmlspecialchars($student["roll_no"]) ?>">

    <label>Room No</label>
    <input name="room" value="<?= htmlspecialchars($student["room"]) ?>">

    <label>Student Contact</label>
    <input name="student_contact" value="<?= htmlspecialchars($student["contact"]["student"] ?? "") ?>">

    <label>Parent Contact</label>
    <input name="parent_contact" value="<?= htmlspecialchars($student["contact"]["parent"] ?? "") ?>">

    <label>Email</label>
    <input name="email" value="<?= htmlspecialchars($student["email"]) ?>">

    <button type="submit">Save Changes</button>
  </form>
</div>
</body>
</html>
