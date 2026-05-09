<?php
session_start();
require_once("../db.php");

use MongoDB\BSON\ObjectId;

/* ======================================================
   ACCESS HANDLING (FIXED, DUAL MODE)
====================================================== */

/* ---------- MODE 1: STUDENT SELF LOGIN ---------- */
if (isset($_SESSION['student'])) {

    $roll = $_SESSION['student']['roll_no'] ?? '';

    if ($roll === '') {
        die("Invalid student session.");
    }

    $student = $db->student_data->findOne([
        "roll_no" => $roll
    ]);

    if (!$student) {
        die("Student record not found.");
    }
}

/* ---------- MODE 2: HOSTEL STAFF VIEW ---------- */
elseif (
    isset($_SESSION['user']) &&
    $_SESSION['user']['type'] === 'HOSTEL_STAFF' &&
    isset($_GET['id'])
) {

    $staffHostel = $_SESSION['user']['hostel'];

    try {
        $student = $db->student_data->findOne([
            "_id"    => new ObjectId($_GET['id']),
            "hostel" => $staffHostel
        ]);
    } catch (Exception $e) {
        die("Invalid student ID.");
    }

    if (!$student) {
        die("Unauthorized access.");
    }
}

/* ---------- INVALID ACCESS ---------- */
else {
    die("Access denied.");
}

/* ---------- FETCH MOVEMENT LOGS ---------- */
$logsCursor = $db->entry_exit_logs->find(
    ["roll" => $student["roll_no"]],
    ["sort" => ["datetime" => -1]]
);

$logs = iterator_to_array($logsCursor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Profile</title>

<style>
:root{
  --bg-main:#071126;
  --bg-secondary:#071024;
  --bg-card:#27242c;
  --accent-primary:#3b82f6;
  --accent-secondary:#06b6d4;
  --text-primary:#e6eef8;
  --text-muted:#98a6bf;
  --success:#10b981;
  --danger:#ef4444;
  --warning:#f59e0b;
  --border-light:rgba(255,255,255,0.08);
}
*{box-sizing:border-box}
body{
  margin:0;
  min-height:100vh;
  font-family:Segoe UI,Arial,sans-serif;
  background:linear-gradient(180deg,var(--bg-main),var(--bg-secondary));
  color:var(--text-primary);
}

/* Page spacing below navbar */
.page-content{
  padding: 24px 22px 30px;
}

.profile{
  max-width:900px;
  margin:30px auto;
  background:var(--bg-card);
  padding:24px;
  border-radius:14px;
  border:1px solid var(--border-light);
}
.profile h2{
  color:var(--accent-secondary);
  margin-top:0
}
.profile p{
  margin:6px 0;
  font-size:15px
}
table{
  width:100%;
  border-collapse:collapse;
  margin-top:14px;
  font-size:14px;
}
th,td{
  padding:10px;
  text-align:center;
}
th{
  background:linear-gradient(90deg,var(--accent-primary),var(--accent-secondary));
  color:#021124;
}
tr{
  border-top:1px solid var(--border-light)
}
tr.in{
  background:rgba(16,185,129,0.12);
  color:var(--success)
}
tr.out{
  background:rgba(239,68,68,0.12);
  color:var(--danger)
}
tr.vacation{
  background:rgba(245,158,11,0.15);
  color:var(--warning)
}
</style>
</head>

<body>

<style>
  :root {
    --bg-main: #071126;
    --bg-card: #121b33;
    --primary: #3b82f6;
    --secondary: #06b6d4;
    --text-main: #e6eef8;
    --text-muted: #9aa8c7;
    --border: rgba(255, 255, 255, 0.12);
    --nav-bg: #081126;
    --nav-btn: #10213f;
    --nav-btn-hover: #13284a;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    background: linear-gradient(180deg, #050c1d, var(--bg-main));
    color: var(--text-main);
    line-height: 1.6;
  }

  .navbar-wrap {
    width: 100%;
    background: var(--nav-bg);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }

  .navbar {
    width: 100%;
    padding: 14px 24px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 18px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    justify-self: start;
  }

  .brand-logo {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .brand-text {
    min-width: 0;
  }

  .brand-title {
    font-size: 20px;
    font-weight: 700;
    color: #3b82f6;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .brand-subtitle {
    font-size: 15px;
    color: var(--text-muted);
    line-height: 1.2;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .nav-center {
    justify-content: center;
    transform: translateX(-80px);
  }

  .nav-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .nav-links a {
    color: #d6dfef;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    color: #e2e8f0;

    padding: 8px 16px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    background: var(--nav-btn);
    border: 1px solid rgba(255, 255, 255, 0.10);
    transition: all 0.2s ease;
  }

  .nav-links a:hover {
    background: var(--nav-btn-hover);
    border-color: rgba(255, 255, 255, 0.16);
    transform: translateY(-1px);
  }

  .nav-links a.active {
    background: var(--nav-btn-hover);
    border-color: rgba(255, 255, 255, 0.16);
    color: #ffffff;
  }

  .nav-icon {
    width: 16px;
    height: 16px;
    display: inline-block;
    flex: 0 0 auto;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    opacity: 0.95;
  }

  @media (max-width: 1100px) {
    .navbar {
      grid-template-columns: 1fr;
      justify-items: start;
    }

    .nav-center {
      width: 100%;
      justify-self: center;
    }

    .brand {
      justify-self: start;
    }
  }

  @media (max-width: 900px) {
    .navbar {
      padding: 12px 14px;
    }

    .nav-links {
      gap: 8px;
    }
  }

  /* DROPDOWN */
  .dropdown {
    position: relative;
  }

  .dropdown > a {
    cursor: pointer;
  }

  .dropdown-menu {
    position: absolute;
    top: 110%;
    left: 0;
    background: #10213f;
    border-radius: 10px;
    padding: 6px 0;
    min-width: 180px;
    display: none;
    flex-direction: column;
    border: 1px solid rgba(255,255,255,0.1);
    z-index: 1000;
  }

  .dropdown-menu a {
    padding: 10px 16px;
    font-size: 14px;
    color: #d6dfef;
    text-decoration: none;
    display: block;
    white-space: nowrap;
  }

  .dropdown-menu a:hover {
    background: #13284a;
  }

  .dropdown:hover .dropdown-menu {
    display: flex;
  }

  .arrow {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin-left: 6px;
    border-right: 2px solid #cbd5e1;
    border-bottom: 2px solid #cbd5e1;
    transform: rotate(45deg);
    transition: transform 0.2s ease;
  }

  .dropdown:hover .arrow {
    transform: rotate(225deg);
  }
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
  padding-top: 2px;
}

.portal-name h1 {
  font-size: 25px;
  margin: 0;
  color: var(--text-primary);
    line-height: 1.1;

}

.portal-name p {
  margin: 6px 0 0;
  font-size: 12px;
  color: var(--text-muted);
    line-height: 1.1;

    
}
.details-title {
  text-align: center;
  margin-bottom: 20px;
  color: var(--accent-secondary);
}

/* 3 COLUMN GRID */
.details-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px 30px;
}

.details-grid p {
  margin: 6px 0;
  font-size: 15px;
}
.details-grid {
  margin-bottom: 35px;
}

.movement-log-title {
  margin-top: 10px;
}

</style>

<div class="navbar-wrap">
  <div class="navbar">

    <div class="brand">
      <div class="brand-logo">
        <img src="../Iiitdmj_logo.jpg" alt="College Logo">
      </div>
      <div class="brand-text">
        <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
        <div class="brand-subtitle">Student Monitoring System</div>
      </div>
    </div>

    <div class="nav-center">
      <nav class="nav-links">
        <a href="../Home/index.php" class="active">Dashboard</a>

        <a href="../logs/role_login.php">Logs</a>

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

    <!-- ONLY ADDITION -->
<div>
  <a href="../Home/index.php"
     onclick="return confirm('Are you sure you want to logout?')"
     style="
       text-decoration:none;
       font-size:16px;
       font-weight:600;
       padding:8px 16px;
       border-radius:10px;
       background:rgba(239,68,68,0.15);
       color:#ef4444;
       border:1px solid rgba(239,68,68,0.4);
     ">
     Logout
  </a>
</div>

  </div>
</div>
<div class="page-top">
  <div class="header-left">
    <img
      src="../student_logo.png"
      alt="logo"
    />
  </div>

  <div class="header-center portal-name">
    <h1>Student Portal</h1>
    <p>Access Your Profile & Activity Logs</p>
  </div>
</div>
<div class="page-content">
  <div class="profile">

    <h2 class="details-title">Student Details</h2>

<div class="details-grid">
  <p><b>Name :</b> <?= htmlspecialchars($student["name"] ?? "") ?></p>
  <p><b>Roll No :</b> <?= htmlspecialchars($student["roll_no"] ?? "") ?></p>
  <p><b>Branch :</b> <?= htmlspecialchars($student["branch"] ?? "") ?></p>

  <p><b>Degree :</b> <?= htmlspecialchars($student["degree"] ?? "") ?></p>
  <p><b>Hostel :</b> <?= htmlspecialchars($student["hostel"] ?? "") ?></p>
  <p><b>Room :</b> <?= htmlspecialchars($student["room"] ?? "") ?></p>

  <p><b>Student Contact :</b> <?= htmlspecialchars($student["contact"]["student"] ?? "") ?></p>
  <p><b>Parent Contact :</b> <?= htmlspecialchars($student["contact"]["parent"] ?? "") ?></p>
  <p><b>Email :</b> <?= htmlspecialchars($student["email"] ?? "") ?></p>
</div>

    <h2>Movement Log</h2>
    <table>
      <tr>
        <th>Type</th>
        <th>Date</th>
        <th>Time</th>
        <th>Purpose</th>
      </tr>

      <?php
      $hasMovement = false;
      foreach ($logs as $log) {

          if (!empty($log["outTime"])) {
              $hasMovement = true;
              [$date, $time] = explode(" ", $log["outTime"]);
              echo "<tr class='out'>
                  <td>OUT</td>
                  <td>$date</td>
                  <td>$time</td>
                  <td>" . htmlspecialchars($log["purpose"] ?? "") . "</td>
              </tr>";
          }

          if (!empty($log["inTime"])) {
              $hasMovement = true;
              [$date, $time] = explode(" ", $log["inTime"]);
              echo "<tr class='in'>
                  <td>IN</td>
                  <td>$date</td>
                  <td>$time</td>
                  <td>" . htmlspecialchars($log["purpose"] ?? "") . "</td>
              </tr>";
          }
      }
      ?>

      <?php if (!$hasMovement): ?>
      <tr><td colspan="4">No movement records found.</td></tr>
      <?php endif; ?>
    </table>

    <h2>Vacation Log</h2>
    <table>
      <tr>
        <th>Type</th>
        <th>Date</th>
        <th>Time</th>
        <th>Purpose</th>
      </tr>

      <?php
      $hasVacation = false;
      foreach ($logs as $log) {

          if (strtolower(trim($log["purpose"] ?? "")) !== "vacation") {
              continue;
          }

          $hasVacation = true;

          if (isset($log["datetime"]) && $log["datetime"] instanceof MongoDB\BSON\UTCDateTime) {
              $dt = $log["datetime"]->toDateTime();
              $date = $dt->format("Y-m-d");
              $time = $dt->format("H:i:s");
          } else {
              $datetimeStr = $log["datetime"] ?? "";
              $parts = explode(" ", $datetimeStr, 2);
              $date = $parts[0] ?? "";
              $time = $parts[1] ?? "";
          }
      ?>
      <tr class="vacation">
        <td><?= htmlspecialchars($log["type"] ?? "") ?></td>
        <td><?= htmlspecialchars($date) ?></td>
        <td><?= htmlspecialchars($time) ?></td>
        <td><?= htmlspecialchars($log["purpose"] ?? "") ?></td>
      </tr>
      <?php } ?>

      <?php if (!$hasVacation): ?>
      <tr><td colspan="4">No vacation records found.</td></tr>
      <?php endif; ?>
    </table>

  </div>
</div>

</body>
</html>