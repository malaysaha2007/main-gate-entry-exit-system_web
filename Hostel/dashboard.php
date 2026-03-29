<?php
session_start();
require '../db.php';

if (
  !isset($_SESSION['user']) ||
  $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
  header("Location: login.php");
  exit;
}

$hostel = $_SESSION['user']['hostel'];
$role = $_SESSION['user']['role'];

$students = $db->entry_exit_logs;

$movementLogs = $students->find([
  'hostel' => $hostel,
  'purpose' => ['$ne' => 'Vacation']
]);

$vacationLogs = $students->find([
  'hostel' => $hostel,
  'purpose' => 'Vacation'
]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hostel Dashboard</title>

  <style>
    :root {
      --bg: #050c1d;
      --nav: #071126;
      --card: #121b33;
      --primary: #3b82f6;
      --secondary: #06b6d4;
      --text: #e6eef8;
      --muted: #9aa8c7;
      --border: rgba(255, 255, 255, 0.12);
    }

    body {
      margin: 0;
      font-family: Inter, system-ui;
      background: linear-gradient(180deg, var(--bg), #071126);
      color: var(--text);
    }

    /* ===== NAVBAR ===== */
    .navbar-wrap {
      padding: 12px 20px;
    }

    .navbar {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(7, 17, 38, 0.85);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 12px 20px;
      backdrop-filter: blur(10px);
    }

    /* LEFT */
    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .brand img {
      width: 42px;
      height: 42px;
      border-radius: 10px;
    }

    .brand-title {
      font-size: 16px;
      font-weight: 600;
    }

    .brand-sub {
      font-size: 11px;
      color: var(--muted);
    }

    /* CENTER */
    .nav-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    .nav-links {
      display: flex;
      gap: 22px;
    }

    .nav-links a {
      color: var(--text);
      text-decoration: none;
      font-size: 14px;
      padding: 8px 14px;
      border-radius: 999px;
      /* FULL ROUND */
      border: 1px solid transparent;
      background: rgba(255, 255, 255, 0.03);
      transition: all 0.2s ease;
    }

    .nav-links a:hover {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid var(--border);
      color: var(--secondary);
    }

    /* RIGHT */
    .nav-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* ROLE BADGE (LIKE IMAGE) */
    .role-box {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 12px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background: rgba(255, 255, 255, 0.03);
    }

    .role-icon {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: #021124;
    }

    .role-text {
      line-height: 1.1;
    }

    .role-title {
      font-size: 13px;
      font-weight: 600;
    }

    .role-sub {
      font-size: 11px;
      color: var(--muted);
    }

    /* LOGOUT BUTTON */
    .logout a {
      padding: 7px 16px;
      border-radius: 15px;
      /* fully rounded */
      border: 1px solid rgba(255, 80, 80, 0.4);
      text-decoration: none;
      color: #ff6b6b;
      font-size: 14px;
      background: rgba(255, 80, 80, 0.08);
      transition: all 0.2s ease;
    }

    .logout a:hover {
      background: rgba(255, 80, 80, 0.18);
      border-color: #ff4d4d;
      color: #ff4d4d;
    }

    /* ===== CONTENT ===== */
    .container {
      padding: 40px;
    }

    .section {
      margin-bottom: 60px;
    }

    .section-title {
      text-align: center;
      font-size: 28px;
    }

    .section-desc {
      text-align: center;
      color: var(--muted);
      margin-bottom: 20px;
    }

    .table-box {
      background: var(--card);
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid var(--border);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid var(--border);
    }

    th {
      background: rgba(59, 130, 246, 0.15);
      color: var(--primary);
    }

    footer {
      text-align: center;
      padding: 20px;
      color: var(--muted);
      border-top: 1px solid var(--border);
    }
  </style>
</head>

<body>

  <!-- ===== NAVBAR ===== -->
  <div class="navbar-wrap">
    <div class="navbar">

      <!-- LEFT -->
      <div class="brand">
        <img src="/PR Project/Iiitdmj_logo.jpg">
        <div>
          <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
          <div class="brand-sub">Student Monitoring System</div>
        </div>
      </div>

      <!-- CENTER -->
      <div class="nav-center">
        <div class="nav-links">
          <a href="/PR Project/Home/index.php">Home</a>
          <a href="\PR Project\Hostel\activity_logs.php">Logs</a>
          <a href="/PR Project/Rules/index.php">Rules</a>
          <a href="/PR Project/camera.php">Camera</a>
        </div>
      </div>

      <!-- RIGHT -->
      <div class="nav-right">

        <div class="role-box">
          <div class="role-icon">
            <?= strtoupper(substr($role, 0, 1)) ?>
          </div>
          <div class="role-text">
            <div class="role-title"><?= htmlspecialchars($role) ?></div>
            <div class="role-sub">Hostel Staff</div>
          </div>
        </div>

        <div class="logout">
          
          <a href="logout.php">Logout</a>
        </div>

      </div>

    </div>
  </div>

  <!-- CONTENT SAME -->
  <div class="container">

    <!-- Movement -->
    <div class="section">
      <h2 class="section-title">Movement Log</h2>
      <p class="section-desc">Records for <?= htmlspecialchars($hostel) ?></p>

      <div class="table-box">
        <table>
          <tr>
            <th>Name</th>
            <th>Roll</th>
            <th>Room</th>
            <th>Purpose</th>
            <th>In</th>
            <th>Out</th>
          </tr>
          <?php $f = false;
          foreach ($movementLogs as $s):
            $f = true; ?>
            <tr>
              <td><?= $s['name'] ?></td>
              <td><?= $s['roll'] ?></td>
              <td><?= $s['room'] ?></td>
              <td><?= $s['purpose'] ?></td>
              <td><?= $s['inTime'] ?></td>
              <td><?= $s['outTime'] ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$f): ?>
            <tr>
              <td colspan="6">No records</td>
            </tr><?php endif; ?>
        </table>
      </div>
    </div>

    <!-- Vacation -->
    <div class="section">
      <h2 class="section-title">Vacation Log</h2>

      <div class="table-box">
        <table>
          <tr>
            <th>Name</th>
            <th>Roll</th>
            <th>Room</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
          </tr>
          <?php $f = false;
          foreach ($vacationLogs as $s):
            $f = true; ?>
            <tr>
              <td><?= $s['name'] ?></td>
              <td><?= $s['roll'] ?></td>
              <td><?= $s['room'] ?></td>
              <td><?= $s['outTime'] ?></td>
              <td><?= $s['inTime'] ?></td>
              <td><?= $s['purpose'] ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$f): ?>
            <tr>
              <td colspan="6">No records</td>
            </tr><?php endif; ?>
        </table>
      </div>
    </div>

  </div>

  <footer>© <?= date('Y') ?> IIITDMJ</footer>

</body>

</html>