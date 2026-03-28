<?php
session_start();
require '../db.php';

/* ---------------- AUTH CHECK ---------------- */
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'HOSTEL_STAFF') {
    header("Location: login.php");
    exit;
}

$hostel = $_SESSION['user']['hostel'];
$role   = $_SESSION['user']['role']; // Warden / Caretaker / Guard

/* ---------------- GUARD BLOCK ---------------- */
if ($role === 'Guard') {
    // Guard should not access activity logs
    header("Location: dashboard.php");
    exit;
}

/* ---------------- ROLE-BASED QUERY ---------------- */
$collection = $db->activity_logs;

$query = ['hostel' => $hostel];

if ($role === 'Caretaker') {
    // Caretaker can see own + Guard
    $query['role'] = ['$in' => ['Caretaker', 'Guard']];
}
// Warden sees everything of that hostel (no extra filter)

$cursor = $collection->find($query, ['sort' => ['timestamp' => -1]]);
$logs = iterator_to_array($cursor);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Hostel Activity Logs</title>

<style>
:root {
  --bg-main:#071126; --bg-secondary:#071024;
  --accent-primary:#3b82f6; --accent-secondary:#06b6d4;
  --text-primary:#e6eef8; --text-dark:#021124;
  --text-muted:#98a6bf; --card-bg:#27242c;
  --border-light:rgba(255,255,255,0.15);
  --radius-md:8px;
}

body {
  font-family: Inter, system-ui, Arial;
  padding:20px;
  min-height:100vh;
  background:linear-gradient(180deg,var(--bg-main),var(--bg-secondary));
  color:var(--text-primary);
}

header {
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.brand h1 {
  margin:0;
  font-size:26px;
}

.brand p {
  margin:0;
  color:var(--text-muted);
}

.logout-btn,.nav-btn {
  padding:8px 14px;
  border-radius:var(--radius-md);
  border:none;
  cursor:pointer;
  font-weight:bold;
  background:linear-gradient(90deg,var(--accent-primary),var(--accent-secondary));
  color:var(--text-dark);
  text-decoration:none;
}

table {
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
}

th,td {
  padding:10px;
  border-bottom:1px solid var(--border-light);
  text-align:center;
}

th {
  color:var(--text-muted);
}

.summary {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
  gap:15px;
  margin-top:25px;
}

.stat {
  background:var(--card-bg);
  padding:15px;
  border-radius:10px;
  text-align:center;
}

footer {
  margin-top:30px;
  text-align:center;
  color:var(--text-muted);
}
</style>
</head>

<body>

<header>
  <div class="brand">
    <h1><?= htmlspecialchars($hostel) ?> — Activity Logs</h1>
    <p>PDPM IIITDMJ student movement logs</p>
    <p style="margin-top:5px;color:var(--accent-secondary);">
        Logged in as <?= htmlspecialchars($role) ?>
    </p>
  </div>

  <div style="display:flex;gap:10px">
    <a href="dashboard.php" class="nav-btn">Back</a>

    <form action="logout.php" method="post">
      <button class="logout-btn">Logout</button>
    </form>
  </div>
</header>

<table>
<thead>
<tr>
  <th>User ID</th>
  <th>Role</th>
  <th>Action</th>
  <th>Description</th>
  <th>Timestamp</th>
</tr>
</thead>
<tbody>

<?php foreach ($logs as $log): ?>
<tr>
  <td><?= htmlspecialchars($log['user_id']) ?></td>
  <td><?= htmlspecialchars($log['role']) ?></td>
  <td><?= htmlspecialchars($log['action_type']) ?></td>
  <td><?= htmlspecialchars($log['description']) ?></td>
  <td><?= htmlspecialchars($log['timestamp']) ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<div class="summary">
  <div class="stat">
      <div>Total Logs</div>
      <div><?= count($logs) ?></div>
  </div>
</div>

<footer>
© <?= date('Y') ?> Main Gate — Hostel Panel
</footer>

</body>
</html>