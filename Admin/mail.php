<?php
session_start();
require '../db.php';

// ---------- AUTH ----------
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

/*
|--------------------------------------------------------------------------
| CURFEW TIME CONFIG
|--------------------------------------------------------------------------
| Change later if needed
*/
$CURFEW_TIME = "10:00"; // 10 AM

// ---------- FETCH LOGS ----------
$logs = $db->entry_exit_logs->find([], [
  'sort' => ['hostel' => 1]
]);

function isCurfewViolated($outTime, $inTime, $curfewTime) {
  if ($inTime) return false;
  if (!$outTime) return false;
  $out = substr($outTime, 11, 5); // HH:MM
  return $out < $curfewTime;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Send Curfew Mail</title>
<meta name="viewport" content="width=device-width,initial-scale=1">

<style>
body {
  background:#071126;
  color:#e6eef8;
  font-family: Inter, system-ui, Arial;
  padding:20px;
}
table {
  width:100%;
  border-collapse:collapse;
  margin-top:15px;
}
th,td {
  padding:10px;
  border-bottom:1px solid rgba(255,255,255,0.15);
}
th { color:#98a6bf; }
.action-btn {
  padding:8px 14px;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-weight:bold;
  background:linear-gradient(90deg,#3b82f6,#06b6d4);
  color:#021124;
}
</style>
</head>

<body>

<h2>Curfew Violation – Send Mail</h2>
<p style="color:#98a6bf">
Students outside after curfew (<b><?php echo $CURFEW_TIME; ?></b>)
</p>

<form method="POST" action="api/send_curfew_mail.php">

<label>
  <input type="checkbox" id="selectAll" checked>
  Select All
</label>

<table>
<thead>
<tr>
  <th></th>
  <th>Name</th><th>Roll</th><th>Hostel</th><th>Room</th>
  <th>Phone</th><th>Email</th>
  <th>Purpose</th><th>Out Time</th><th>In Time</th>
</tr>
</thead>
<tbody>
<?php foreach ($logs as $row): ?>
<?php if (isCurfewViolated($row['outTime'] ?? null, $row['inTime'] ?? null, $CURFEW_TIME)): ?>
<tr>
  <td>
    <input type="checkbox" class="chk" name="students[]" checked
  value='<?php echo json_encode([
    "name"     => $row["name"] ?? "",
    "roll"     => $row["roll"] ?? "",
    "email"    => $row["email"] ?? "",
    "hostel"   => $row["hostel"] ?? "",
    "room"     => $row["room"] ?? "",
    "phone"    => $row["phone"] ?? "",
    "purpose"  => $row["purpose"] ?? "",
    "outTime"  => $row["outTime"] ?? "",
    "inTime"   => $row["inTime"] ?? ""
  ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>

  </td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['roll']) ?></td>
  <td><?= htmlspecialchars($row['hostel']) ?></td>
  <td><?= htmlspecialchars($row['room']) ?></td>
  <td><?= htmlspecialchars($row['phone']) ?></td>
  <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
  <td><?= htmlspecialchars($row['purpose']) ?></td>
  <td><?= htmlspecialchars($row['outTime']) ?></td>
  <td><?= htmlspecialchars($row['inTime']) ?></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</tbody>
</table>

<br>
<button type="submit" class="action-btn">Confirm & Send Mail</button>

</form>

<script>
document.getElementById('selectAll').onchange = e => {
  document.querySelectorAll('.chk').forEach(c => c.checked = e.target.checked);
};
</script>

</body>
</html>
