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
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Import Students | <?= htmlspecialchars($hostel) ?></title>

<style>
:root {
  --bg-main: #071126;
  --bg-card: #121b33;
  --primary: #3b82f6;
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

/* NAVBAR */
.navbar-wrap {
  width:100%;
  background:#081126;
  border-bottom:1px solid rgba(255,255,255,0.08);
  position: relative;
  z-index: 1000;
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
  height:50px;
}

.brand-title {
  font-size:20px;
  font-weight:700;
  color:#3b82f6;
}

.brand-subtitle {
  font-size:13px;
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
  border-radius:10px;
  background:#10213f;
  border:1px solid rgba(255,255,255,0.1);
  color:#e2e8f0;
  text-decoration:none;
  font-weight:600;
}

.nav-links a:hover {
  background:#13284a;
}

/* DROPDOWN FIXED */
.dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 45px;
  left: 0;
  background: #10213f;
  border-radius: 10px;
  display: none;
  flex-direction: column;
  min-width: 180px;
  padding: 6px 0;
  border: 1px solid rgba(255,255,255,0.08);
  z-index: 9999;
}

.dropdown:hover .dropdown-menu {
  display: flex;
}

.dropdown-menu a {
  padding: 10px 16px;
}

.dropdown-menu a:hover {
  background:#13284a;
}

/* ROLE + LOGOUT */
.nav-role-badge {
  display:flex;
  align-items:center;
  gap:10px;
  background:rgba(255,255,255,0.05);
  padding:6px 12px;
  border-radius:25px;
}

.nav-role-icon {
  width:34px;
  height:34px;
  border-radius:50%;
  background:#2b6cb0;
  display:flex;
  align-items:center;
  justify-content:center;
  color:white;
}

.logout a {
  padding:8px 14px;
  border-radius:10px;
  background:rgba(239,68,68,0.15);
  color:#ef4444;
  text-decoration:none;
}

/* PAGE */
.container {
  max-width:900px;
  margin:50px auto;
  padding:20px;
}

.card {
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:14px;
  padding:30px;
}

/* FILE INPUT */
.file-box {
  margin-bottom:20px;
  padding:15px;
  border:1px dashed rgba(255,255,255,0.2);
  border-radius:10px;
  text-align:center;
  color:var(--text-muted);
}

input[type="file"] {
  margin-top:10px;
  color:#9aa8c7;
}

/* BUTTON */
.btn {
  padding:10px 18px;
  border-radius:999px;
  font-size:14px;
  font-weight:600;
  border:1px solid rgba(59,130,246,0.5);
  color:#3b82f6;
  background:transparent;
  cursor:pointer;
}

.btn:hover {
  background:rgba(59,130,246,0.15);
}

/* RESULT */
.result-box {
  margin-top:20px;
  padding:12px;
  border-radius:10px;
}

.success { background:rgba(34,197,94,0.15); }
.error { background:rgba(239,68,68,0.15); }
.duplicate { background:rgba(234,179,8,0.15); }

table {
  width:100%;
  margin-top:20px;
  border-collapse:collapse;
}

th, td {
  padding:12px;
  border-bottom:1px solid var(--border);
  text-align:center;
}

.hidden { display:none; }
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
  <div class="nav-links">
    <a href="../Home/index.php">Home</a>
    <a href="../Hostel/dashboard.php">Dashboard</a>
    <a href="../logs/role_login.php">Logs</a>

    <div class="dropdown">
      <a href="#">Login</a>
      <div class="dropdown-menu">
        <a href="../Student/student_login.php">Student Login</a>
        <a href="../Hostel/login.php">Hostel Login</a>
        <a href="../Admin/login.php">Admin Login</a>
      </div>
    </div>

    <a href="../Rules/index.php">Rules</a>
  </div>
</div>

<div style="display:flex; gap:10px; align-items:center;">
  <div class="nav-role-badge">
    <div class="nav-role-icon"><?= strtoupper(substr($role,0,1)) ?></div>
    <div><?= htmlspecialchars($role) ?></div>
  </div>

  <div class="logout">
    <a href="logout.php">Logout</a>
  </div>
</div>

  </div>
</div>

<!-- CONTENT -->

<div class="container">

<h2 style="text-align:center;margin-bottom:30px;">
Import Students – <?= htmlspecialchars($hostel) ?>
</h2>

<div class="card">

  <div class="file-box">
    Upload Excel File
    <br>
    <input type="file" id="file" accept=".xlsx,.xls">
  </div>

<button class="btn" onclick="upload()">Import Excel</button>

</div>

<!-- RESULT -->

<div id="result" class="hidden">

  <div class="result-box success">
    ✔ <span id="success"></span> Students Imported
  </div>

  <div class="result-box duplicate">
    ⚠ <span id="duplicate"></span> Already Exist
  </div>

  <div class="result-box error">
    ❌ <span id="error"></span> Errors Found
  </div>

  <div id="errorTable" class="hidden">
    <table>
      <tr><th>Row</th><th>Roll</th><th>Issue</th></tr>
      <tbody id="errorBody"></tbody>
    </table>
  </div>

</div>

</div>

<script>
function upload(){
  let file = document.getElementById("file").files[0];
  if(!file){ alert("Select file"); return; }

  let fd = new FormData();
  fd.append("file", file);

  fetch("import_students_backend.php", {
    method:"POST",
    body:fd
  })
  .then(res=>res.json())
  .then(data=>{
    document.getElementById("result").classList.remove("hidden");

    document.getElementById("success").innerText = data.success_count;
    document.getElementById("duplicate").innerText = data.duplicate_count;
    document.getElementById("error").innerText = data.error_count;

    if(data.errors.length>0){
      let html="";
      data.errors.forEach(e=>{
        html += `<tr>
          <td>${e.row}</td>
          <td>${e.roll}</td>
          <td>${e.issue}</td>
        </tr>`;
      });
      document.getElementById("errorBody").innerHTML = html;
      document.getElementById("errorTable").classList.remove("hidden");
    }
  });
}
</script>

</body>
</html>
