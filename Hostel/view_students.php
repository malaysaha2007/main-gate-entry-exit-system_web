<?php
session_start();
require '../db.php';

/* ---------------- AUTH CHECK ---------------- */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    header("Location: login.php");
    exit;
}

$hostel = $_SESSION['user']['hostel'];
$role   = $_SESSION['user']['role'];

/* ---------------- FETCH STUDENTS ---------------- */
$students = $db->student_data->find([
    'hostel' => $hostel
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Students | <?= htmlspecialchars($hostel) ?></title>

<style>
:root {
  --bg-main: #071126;
  --bg-card: #121b33;
  --primary: #3b82f6;
  --secondary: #06b6d4;
  --text-main: #e6eef8;
  --text-muted: #9aa8c7;
  --border: rgba(255,255,255,0.12);
}

*{box-sizing:border-box;margin:0;padding:0;}

body{
  font-family:Inter,system-ui,Arial;
  background:linear-gradient(180deg,#050c1d,var(--bg-main));
  color:var(--text-main);
}

/* ================= NAVBAR (FIXED) ================= */
.navbar-wrap {
  width: 100%;
  background: #081126;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}

.navbar {
  display: flex;
  align-items: center;
  padding: 12px 24px;
}

/* LEFT */
.brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand-logo img {
  width: 50px;
  height: 50px;
  object-fit: contain;
}

.brand-title {
  font-size: 20px;
  font-weight: 700;
  color: #3b82f6;
}

.brand-subtitle {
  font-size: 13px;
  color: var(--text-muted);
}

/* CENTER */
.nav-center {
  flex: 1;
  display: flex;
  justify-content: center;
}

/* NAV LINKS (MAIN FIX HERE) */
.nav-links {
  display: flex;
  align-items: center;   /* IMPORTANT */
  gap: 10px;
}

.nav-links a {
  display: flex;         /* IMPORTANT */
  align-items: center;   /* IMPORTANT */
  height: 38px;          /* SAME HEIGHT FOR ALL */
  text-decoration: none;
  font-size: 15px;
  font-weight: 600;
  color: #e2e8f0;
  padding: 0 14px;
  border-radius: 10px;
  background: #10213f;
  border: 1px solid rgba(255,255,255,0.1);
}

.nav-links a:hover {
  background: #13284a;
}

/* DROPDOWN */
.dropdown {
  position: relative;
  display: flex;
  align-items: center; /* FIX */
}

.dropdown-menu {
  position: absolute;
  top: 110%;
  left: 0;
  background: #10213f;
  border-radius: 10px;
  display: none;
  flex-direction: column;
  min-width: 180px;
  z-index: 100;
}

.dropdown:hover .dropdown-menu {
  display: flex;
}

.dropdown-menu a {
  padding: 10px 16px;
  height: auto;
}

/* ARROW */
.arrow {
  margin-left: 6px;
  border: solid #cbd5e1;
  border-width: 0 2px 2px 0;
  display: inline-block;
  padding: 3px;
  transform: rotate(45deg);
}
.nav-role-badge {
    display: flex;
    align-items: center;
    gap: 10px;

    background: rgba(255, 255, 255, 0.05);
    padding: 6px 12px;
    border-radius: 25px;

    border: 1px solid rgba(255, 255, 255, 0.08);

    /* IMPORTANT FIX */
    width: fit-content;
    white-space: nowrap;
}

.nav-role-icon {
    width: 34px;
    height: 34px;

    border-radius: 50%;
    background: #2b6cb0;

    display: flex;
    align-items: center;
    justify-content: center;

    color: white;
    font-weight: 600;
    font-size: 14px;

    flex-shrink: 0;
}

.nav-role-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.nav-role-title {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
}

.nav-role-sub {
    font-size: 12px;
    color: #a0aec0;
}

/* RIGHT */
.logout {
  margin-left: auto;
}

.logout a {
  display: flex;
  align-items: center;
  height: 38px;
  padding: 0 14px;
  text-decoration: none;
  font-size: 15px;
  font-weight: 600;
  border-radius: 10px;
  background: rgba(239,68,68,0.15);
  color: #ef4444;
  border: 1px solid rgba(239,68,68,0.4);
}

.logout a:hover {
  background: rgba(239,68,68,0.3);
  color: white;
}

/* ================= PAGE ================= */
.page-top {
  display: flex;
  align-items: center;
  padding: 16px 24px;
  gap: 15px;
}

.header-left img {
  width: 50px;
}

.portal-name h1 {
  font-size: 24px;
}

.portal-name p {
  font-size: 12px;
  color: var(--text-muted);
}

.container{
  max-width:1100px;
  margin:40px auto;
  padding:0 20px;
}

.page-title{
  text-align:center;
  font-size:34px;
}

.page-sub{
  text-align:center;
  color:var(--text-muted);
  margin-bottom:40px;
}

.card{
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:14px;
  padding:28px;
}

.add-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;

  padding: 10px 20px;
  border-radius: 999px;

  background: transparent; /* 🔥 TRANSPARENT */
  color: #3b82f6;

  font-size: 14px;
  font-weight: 600;
  text-decoration: none;

  border: 1px solid rgba(59,130,246,0.5);

  backdrop-filter: blur(6px); /* glass effect */
  transition: all 0.25s ease;
}
.add-btn:hover {
  background: rgba(59,130,246,0.15);
  border-color: #3b82f6;
  color: #60a5fa;
  box-shadow: 0 0 12px rgba(59,130,246,0.4);
}
.add-btn:active {
  transform: scale(0.96);
}

table{
  width:100%;
  border-collapse:collapse;
}

th,td{
  padding:14px;
  border-bottom:1px solid var(--border);
}

th{
  color:var(--text-muted);
}

.actions {
  display: flex;
  justify-content: center;  /* CENTER HORIZONTALLY */
  align-items: center;
  gap: 8px;
}

/* COMMON BUTTON STYLE */
.actions a {
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 13px;
  text-decoration: none;
  font-weight: 500;
  border: 1px solid transparent;
  transition: all 0.2s ease;
}

/* VIEW */
.actions a.view {
  background: rgba(6,182,212,0.15);
  color: #06b6d4;
  border-color: rgba(6,182,212,0.4);
}

.actions a.view:hover {
  background: rgba(6,182,212,0.3);
}

/* EDIT */
.actions a.edit {
  background: rgba(34,197,94,0.15);
  color: #22c55e;
  border-color: rgba(34,197,94,0.4);
}

.actions a.edit:hover {
  background: rgba(34,197,94,0.3);
}

/* DELETE */
.actions a.delete {
  background: rgba(239,68,68,0.15);
  color: #ef4444;
  border-color: rgba(239,68,68,0.4);
}

.actions a.delete:hover {
  background: rgba(239,68,68,0.3);
}
th, td {
  padding: 14px;
  border-bottom: 1px solid var(--border);
  text-align: center;   /* 🔥 CENTER EVERYTHING */
  vertical-align: middle;
}
table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}
footer{
  margin-top:80px;
  border-top:1px solid var(--border);
  padding:30px;
  text-align:center;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-wrap">
  <div class="navbar">

    <div class="brand">
      <div class="brand-logo">
        <img src="../Iiitdmj_logo.jpg">
      </div>
      <div>
        <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
        <div class="brand-subtitle">Student Monitoring System</div>
      </div>
    </div>

    <div class="nav-center">
      <nav class="nav-links">

        <a href="../Home/index.php">Home</a>
        <a href="../Hostel\dashboard.php">Dashboard</a>

        <a href="logs/role_login.php">Logs</a>

        <div class="dropdown">
          <a href="#">Login <span class="arrow"></span></a>
          <div class="dropdown-menu">
            <a href="../Student/student_login.php">Student Login</a>
            <a href="../Hostel/login.php">Hostel Login</a>
            <a href="../Admin/login.php">Admin Login</a>
          </div>
        </div>

        <a href="../Rules/index.php">Rules</a>

      </nav>
    </div>

    <div style="display:flex; align-items:center; gap:12px; margin-left:auto;">

  <!-- ROLE BADGE (NAVBAR) -->
  <div class="nav-role-badge">
    <div class="nav-role-icon">
      <?= strtoupper(substr($role, 0, 1)) ?>
    </div>
    <div class="nav-role-text">
      <div class="nav-role-title"><?= htmlspecialchars($role) ?></div>
      <div class="nav-role-sub">Hostel Staff</div>
    </div>
  </div>

  <!-- LOGOUT -->
  <div class="logout">
    <a href="logout.php"
       onclick="return confirm('Are you sure you want to logout?')">
      Logout
    </a>
  </div>

</div>

  </div>
</div>


<!-- HEADER -->
<div class="page-top">
  <div class="header-left">
    <img src="../hostel_logo.jpg">
  </div>
  <div class="portal-name">
    <h1>Hostel Portal</h1>
    <p>Manage Students & Monitor Activity</p>
  </div>
</div>

<!-- CONTENT -->
<div class="container">

<h1 class="page-title" style="margin-bottom: 50px;">
    Students – <?= htmlspecialchars($hostel) ?>
</h1>
  <div class="card">

    <?php if ($role === 'Warden' || $role === 'Caretaker'): ?>
      <a href="import_students.php" class="add-btn">+ Add Student</a>
    <?php endif; ?>

    <table>
      <tr>
        <th>Name</th>
        <th>Roll No</th>  
        <th>Room</th>
        <th>Actions</th>
      </tr>

      <?php foreach ($students as $student): ?>
      <tr>
        <td><?= htmlspecialchars($student['name'] ?? '') ?></td>
        <td><?= htmlspecialchars($student['roll_no'] ?? '') ?></td>
        <td><?= htmlspecialchars($student['room'] ?? '') ?></td>
        <td class="actions">
          <a class="view" href="../Student/student_profile.php?id=<?= (string)$student['_id'] ?>">View</a>

          <?php if ($role === 'Warden' || $role === 'Caretaker'): ?>
            <a class="edit" href="../Student/edit_profile.php?id=<?= (string)$student['_id'] ?>">Edit</a>
            <a class="delete" href="../Student/delete_student.php?id=<?= (string)$student['_id'] ?>"
               onclick="return confirm('Are you sure?');">Delete</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

  </div>
</div>

<footer>
  © 2025 PDPM IIITDMJ | Student Entry–Exit Management System
</footer>

</body>
</html>