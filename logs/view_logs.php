<?php
session_start();
require_once("../db.php");
require_once("access_control.php");

/* ---------- AUTH CHECK ---------- */
if (!isset($_SESSION["role"])) {
    die("Access denied. Please login.");
}

$role   = $_SESSION["role"];
$hostel = $_SESSION["hostel"] ?? null;

/* ---------- GET FILTER FROM CENTRAL LOGIC ---------- */
$filter = buildLogFilter($role, $hostel);

/* ---------- FETCH LOGS ---------- */
$logsCursor = $db->activity_logs->find(
    $filter,
    ["sort" => ["timestamp" => -1]]
);

$logs = iterator_to_array($logsCursor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Activity Logs | PDPM IIITDMJ</title>

<style>
:root {
  --bg-main: #071126;
  --bg-secondary: #071024;
  --bg-card: #27242c;
  --bg-input: #211f1f;

  --accent-primary: #3b82f6;
  --accent-secondary: #06b6d4;

  --text-primary: #e6eef8;
  --text-muted: #98a6bf;
  --text-dark: #021124;

  --border-light: rgba(255,255,255,0.08);

  --radius-md: 10px;
  --radius-lg: 14px;

  --shadow-main: 0 8px 28px rgba(119,202,202,0.4);
}

* {
  box-sizing: border-box;
  font-family: "Segoe UI", sans-serif;
}

body {
  margin: 0;
  min-height: 100vh;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
}

/* 🔹 HEADER */
.page-top {
  display: flex;
  align-items: flex-start;
  padding: 16px 24px 0;
  gap: 18px;
}

.header-left img {
  width: 50px;
  height: 50px;
  border-radius: 8px;
}

.header-center {
  flex: 1;
}

.portal-name h1 {
  font-size: 25px;
  margin: 0;
}

.portal-name p {
  margin: 6px 0 0;
  font-size: 12px;
  color: var(--text-muted);
}

/* 🔹 ROLE BADGE */
.top-right-bar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
  padding: 10px 24px;
}

.nav-role-badge {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255, 255, 255, 0.05);
  padding: 6px 12px;
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.08);
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
}

.nav-role-text {
  display: flex;
  flex-direction: column;
  line-height: 1.1;
}

.nav-role-title {
  font-size: 14px;
  font-weight: 600;
}

.nav-role-sub {
  font-size: 12px;
  color: #a0aec0;
}

.logout a {
  padding: 8px 14px;
  border-radius: 10px;
  background: rgba(239,68,68,0.15);
  color: #ef4444;
  text-decoration: none;
  border: 1px solid rgba(239,68,68,0.4);
}

.logout a:hover {
  background: rgba(239,68,68,0.3);
  color: white;
}

/* 🔹 CONTENT */
.container {
  padding: 30px;
}

.table-card {
  margin-top: 20px;
  background: var(--bg-card);
  border-radius: var(--radius-lg);
  padding: 20px;
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
}

.header-text p {
  color: var(--text-muted);
  margin-top: 6px;
}

/* 🔹 TABLE */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

th, td {
  padding: 12px 10px;
  border-bottom: 1px solid var(--border-light);
  text-align: left;
  font-size: 14px;
}

th {
  color: var(--accent-secondary);
}

tr:hover {
  background: #020617;
}

/* 🔹 BADGES */
.badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  background: var(--accent-primary);
  color: white;
}

.badge.global {
  background: #16a34a;
}

/* 🔹 EMPTY */
.empty {
  text-align: center;
  padding: 40px;
  color: var(--text-muted);
}

/* 🔹 FOOTER */
footer {
  text-align: center;
  margin-top: 40px;
  font-size: 14px;
  color: var(--text-muted);
}
</style>
</head>

<body>

<!-- ✅ NAVBAR (NOW SAME AS login.php) -->
<?php include '../navbar.php'; ?>

<!-- HEADER -->
<div class="page-top">
  <div class="header-left">
    <img src="../student_logo.png" alt="logo">
  </div>

  <div class="header-center portal-name">
    <h1>System Activity Logs</h1>
    <p>Monitor Student Movement & System Actions</p>
  </div>
</div>

<!-- ROLE BADGE -->
<div class="top-right-bar">
  <div class="nav-role-badge">
    <div class="nav-role-icon">
      <?= strtoupper(substr($role, 0, 1)) ?>
    </div>
    <div class="nav-role-text">
      <div class="nav-role-title"><?= htmlspecialchars($role) ?></div>
      <div class="nav-role-sub">Hostel Staff</div>
    </div>
  </div>

 
</div>

<div class="container">

  <div class="header-text">
    <p>
      Logged in as:
      <b><?= htmlspecialchars($role) ?></b>
      <?php if ($hostel): ?>
        | Hostel: <b><?= htmlspecialchars($hostel) ?></b>
      <?php endif; ?>
    </p>
  </div>

  <div class="table-card">

  <?php if (count($logs) === 0): ?>
    <div class="empty">No logs found.</div>
  <?php else: ?>

  <table>
  <thead>
  <tr>
    <th>Date & Time</th>
    <th>User</th>
    <th>Role</th>
    <th>Hostel</th>
    <th>Action</th>
  </tr>
  </thead>

  <tbody>
  <?php foreach ($logs as $log): ?>
  <tr>
    <td><?= htmlspecialchars($log["timestamp"]) ?></td>
    <td><?= htmlspecialchars($log["user_id"]) ?></td>
    <td><?= htmlspecialchars($log["role"]) ?></td>
    <td>
      <?php if (!empty($log["hostel"])): ?>
        <span class="badge"><?= htmlspecialchars($log["hostel"]) ?></span>
      <?php else: ?>
        <span class="badge global">GLOBAL</span>
      <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($log["description"]) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <?php endif; ?>

  </div>
</div>

<footer>
  <h4>&copy; 2025 System Portal. All Rights Reserved.</h4>
</footer>

</body>
</html>