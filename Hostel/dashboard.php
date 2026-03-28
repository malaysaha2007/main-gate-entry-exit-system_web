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

/* ---------------- SESSION DATA ---------------- */
$hostel = $_SESSION['user']['hostel'];
$role   = $_SESSION['user']['role']; // Warden / Caretaker / Guard

/* ---------------- FETCH STUDENTS ---------------- */
$students = $db->entry_exit_logs;

/* Movement Log → NOT Vacation */
$movementLogs = $students->find([
    'hostel' => $hostel,
    'purpose' => ['$ne' => 'Vacation']
]);

/* Vacation Log → Vacation only */
$vacationLogs = $students->find([
    'hostel' => $hostel,
    'purpose' => 'Vacation'
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>PDPM IIITDMJ | Hostel Dashboard</title>

<style>
:root {
  --bg-main: #071126;
  --bg-card: #121b33;
  --primary: #3b82f6;
  --secondary: #06b6d4;
  --text-main: #e6eef8;
  --text-muted: #9aa8c7;
  --border: rgba(255, 255, 255, 0.12);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
  background: linear-gradient(180deg, #050c1d, var(--bg-main));
  color: var(--text-main);
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 40px;
  border-bottom: 1px solid var(--border);
}
.logo { font-size: 22px; font-weight: 700; color: var(--primary); }

nav {
  display:flex;
  gap:20px;
  align-items:center;
}

nav a, nav button {
  color: var(--text-main);
  text-decoration: none;
  background:none;
  border:none;
  font-size:15px;
  cursor:pointer;
}

nav a:hover, nav button:hover {
  color: var(--secondary);
}

.container { padding: 60px 40px; }
.section { margin-bottom: 70px; }
.section-title { font-size: 30px; text-align: center; margin-bottom: 8px; }
.section-desc { text-align: center; color: var(--text-muted); margin-bottom: 34px; }

.table-box {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 14px;
}

table { width: 100%; border-collapse: collapse; }

th, td {
  padding: 14px;
  text-align: center;
  border-bottom: 1px solid var(--border);
}

th {
  background: rgba(59, 130, 246, 0.15);
  color: var(--primary);
}

footer {
  border-top: 1px solid var(--border);
  padding: 30px 40px;
  text-align: center;
  color: var(--text-muted);
}
</style>
</head>

<body>

<header>
  <div class="logo">
    PDPM IIITDMJ Hostel Dashboard
    <div style="font-size:14px;color:var(--text-muted);margin-top:4px;">
        Logged in as <?= htmlspecialchars($role) ?>
    </div>
  </div>

  <nav>

    <!-- SHOW ONLY IF NOT GUARD -->
    <?php if ($role !== 'Guard'): ?>
        <a href="activity_logs.php">Activity Logs</a>
    <?php endif; ?>

    <form action="logout.php" method="post" style="display:inline;">
        <button type="submit">Logout</button>
    </form>

  </nav>
</header>

<div class="container">

<!-- ================= MOVEMENT LOG ================= -->

<div class="section">
  <h2 class="section-title">Movement Log</h2>
  <p class="section-desc">
      Student entry and exit records for <?= htmlspecialchars($hostel) ?>
  </p>

  <div class="table-box">
    <table>
      <thead>
        <tr>
          <th>Student Name</th>
          <th>Roll No</th>
          <th>Room No</th>
          <th>Purpose</th>
          <th>Entry Time</th>
          <th>Exit Time</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>

      <?php $found = false; foreach ($movementLogs as $s): $found = true; ?>
        <tr>
          <td><?= htmlspecialchars($s['name'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['roll'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['room'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['purpose'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['inTime'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['outTime'] ?? '-') ?></td>
          <td><?= htmlspecialchars(isset($s['outTime']) ? substr($s['outTime'],0,10) : '-') ?></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$found): ?>
        <tr><td colspan="7">No movement records found</td></tr>
      <?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

<!-- ================= VACATION LOG ================= -->

<div class="section">
  <h2 class="section-title">Vacation Log</h2>
  <p class="section-desc">
      Approved student vacations for <?= htmlspecialchars($hostel) ?>
  </p>

  <div class="table-box">
    <table>
      <thead>
        <tr>
          <th>Student Name</th>
          <th>Roll No</th>
          <th>Room No</th>
          <th>From Date</th>
          <th>To Date</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>

      <?php $found = false; foreach ($vacationLogs as $s): $found = true; ?>
        <tr>
          <td><?= htmlspecialchars($s['name'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['roll'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['room'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['outTime'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['inTime'] ?? '-') ?></td>
          <td><?= htmlspecialchars($s['purpose'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$found): ?>
        <tr><td colspan="6">No vacation records found</td></tr>
      <?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

</div>

<footer>
© <?= date('Y') ?> PDPM IIITDMJ — Student Entry–Exit Management System
</footer>

</body>
</html>