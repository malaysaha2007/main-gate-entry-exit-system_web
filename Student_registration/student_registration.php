<?php
session_start();

/* ================= AUTH CHECK ================= */
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['type'] !== 'HOSTEL_STAFF'
) {
    die("Access denied");
}

$role = $_SESSION['user']['role'];
if (!in_array($role, ['Warden', 'Caretaker'])) {
    die("Access denied");
}

$username    = $_SESSION['user']['username'];
$staffHostel = $_SESSION['user']['hostel'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Registration | PDPM IIITDMJ</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root {
  --bg-main:#071126;
  --text-muted:#9aa8c7;
}

/* ===== GLOBAL ===== */
*{box-sizing:border-box;margin:0;padding:0;font-family:"Segoe UI",sans-serif;}
body{
  min-height:100vh;
  background:linear-gradient(135deg,#020617,#020b2d,#020617);
  color:#e5e7eb;
}

/* ===== NAVBAR ===== */
.navbar-wrap {
  width:100%;
  background:#081126;
  border-bottom:1px solid rgba(255,255,255,0.08);
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
  border-radius: 8px;
}

.brand-title {
  font-size:18px;
  font-weight:700;
  color:#3b82f6;
}

.brand-subtitle {
  font-size:12px;
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
  text-decoration:none;
  color:#e2e8f0;
  background:#10213f;
  border-radius:10px;
  font-size:14px;
}

.dropdown { position:relative; }

.dropdown-menu {
  position: absolute;
  top: 110%;
  left: 0;
  display: none;
  flex-direction: column;
  min-width: 180px; /* FIX WIDTH */
  background: #10213f;
  border-radius: 12px;
  padding: 8px; /* ADD SPACE */
  gap: 6px; /* SPACE BETWEEN ITEMS */
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  z-index: 100;
}
.dropdown-menu a {
  display: block;
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  text-decoration: none;
  color: #e2e8f0;
  font-size: 14px;
}

.dropdown-menu a:hover {
  background: rgba(255,255,255,0.08);
}

.dropdown:hover .dropdown-menu { display:flex; }

.arrow {
  margin-left:6px;
  border:solid #cbd5e1;
  border-width:0 2px 2px 0;
  padding:3px;
  transform:rotate(45deg);
}

/* ROLE BADGE */
.nav-role-badge {
  display:flex;
  align-items:center;
  gap:10px;
  font-size: 14px;
  padding:6px 12px;
  border-radius:25px;
  background:rgba(255,255,255,0.05);
}
.nav-role-badge small {
  color: var(--text-muted);
  font-size: 11px;
}

.nav-role-icon {
  width:34px;height:34px;
  border-radius:50%;
  background:#2b6cb0;
  display:flex;
  align-items:center;
  justify-content:center;
  color:white;
}

/* LOGOUT */
.logout a {
  display:flex;
  align-items:center;
  height:38px;
  padding:0 14px;
  border-radius:10px;
  background:rgba(239,68,68,0.2);
  color:#ef4444;
  text-decoration:none;
}

/* ===== HEADER ===== */
.page-top {
  display:flex;
  align-items:center;
  gap:6px;
  padding:16px 24px;
}

.header-left img {
  display: block;
  width: 50px;
  border-radius: 5px;
  
}

.portal-name h1 { font-size:22px; }
.portal-name p { font-size:12px;color:var(--text-muted); }

/* ===== PAGE ===== */
.container{max-width:1000px;margin:40px auto;padding:10px;}
h1{text-align:center;}
.subtitle{text-align:center;color:#9ca3af;margin-bottom:30px;}

.card{
  background:rgba(15,23,42,.9);
  border-radius:14px;
  padding:30px;
}

.form-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:20px;
}

input,select{
  width:100%;padding:12px;border-radius:8px;
  background:#020617;border:1px solid #1e293b;color:white;
}

.upload-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;margin-top:25px;
}

.upload-box{
  border:2px dashed #334155;
  padding:20px;
  text-align:center;
  border-radius:10px;
}

.buttons{
  margin-top:30px;
  display:flex;
  justify-content:center;
  gap:20px;
}

button{
  padding:12px 30px;
  border:none;
  border-radius:10px;
  cursor:pointer;
}

.submit{background:#2563eb;color:white;}
.cancel{background:#334155;color:white;}

.logged-user{
  text-align:right;
  margin-bottom:15px;
  font-size:14px;
  color:#22d3ee;
}

/* MODAL */
#modal{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.75);
}

.modal-box{
  background:#020617;
  max-width:850px;
  margin:60px auto;
  padding:30px;
  border-radius:16px;
}

.modal-details{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:10px 20px;
}

.modal-photos{
  display:flex;
  justify-content:center;
  gap:20px;
  margin-top:25px;
}

.modal-photos img{
  width:160px;height:160px;
  object-fit:cover;
  border-radius:12px;
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<div class="navbar-wrap">
  <div class="navbar">

    <div class="brand">
      <div class="brand-logo">
        <img src="/PR Project/Iiitdmj_logo.jpg">
      </div>
      <div>
        <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
        <div class="brand-subtitle">Student Monitoring System</div>
      </div>
    </div>

    <div class="nav-center">
      <nav class="nav-links">
        <a href="/PR Project/Home/index.php">Dashboard</a>
        <a href="/PR Project/logs/role_login.php">Logs</a>

        <div class="dropdown">
          <a href="#">Login <span class="arrow"></span></a>
          <div class="dropdown-menu">
            <a href="/PR Project/Student/student_login.php">Student Login</a>
            <a href="/PR Project/Hostel/login.php">Hostel Login</a>
            <a href="/PR Project/Admin/login.php">Admin Login</a>
          </div>
        </div>

        <a href="/PR Project/Rules/index.php">Rules</a>
        <a href="/PR Project/camera.php">Camera</a>
      </nav>
    </div>

    <div style="display:flex;align-items:center;gap:12px;margin-left:auto;">
      <div class="nav-role-badge">
        <div class="nav-role-icon">
          <?= strtoupper(substr($role, 0, 1)) ?>
        </div>
        <div>
          <div><?= htmlspecialchars($role) ?></div>
          <small>Hostel Staff</small>
        </div>
      </div>

      <div class="logout">
        <a href="/PR Project/logout.php">Logout</a>
      </div>
    </div>

  </div>
</div>

<!-- ===== HEADER ===== -->
<div class="page-top">
  <div class="header-left">
    <img src="/PR Project/hostel_logo.jpg">
  </div>
  <div class="portal-name">
    <h1>Hostel Portal</h1>
    <p>Manage Students & Monitor Activity</p>
  </div>
</div>

<!-- ===== CONTENT ===== -->
<div class="container">



<h1>Student Registration</h1>
<div class="subtitle">PDPM IIITDMJ Entry–Exit Management System</div>

<div class="card">
<form id="form" action="save_student.php" method="POST" enctype="multipart/form-data">

<div class="form-grid">
<input id="name" name="name" placeholder="Student Name">
<input id="roll" name="roll" placeholder="Roll No">
<input id="branch" name="branch" placeholder="Branch">

<select id="degree" name="degree">
  <option value="">Select Degree</option>
  <option>B.Tech</option>
  <option>M.Tech</option>
  <option>PhD</option>
</select>

<input id="hostel" name="hostel" value="<?= htmlspecialchars($staffHostel) ?>" readonly>
<input id="room" name="room" placeholder="Room No">

<input id="studentNo" name="studentNo" placeholder="Student Contact No">
<input id="parentNo" name="parentNo" placeholder="Parent Contact No">

<input id="email" name="email" placeholder="Student Email">
<input id="password" name="password" type="password" placeholder="Password">
</div>

<div class="upload-grid">
  <div class="upload-box">Photo 1<input type="file" id="p1" name="p1"></div>
  <div class="upload-box">Photo 2<input type="file" id="p2" name="p2"></div>
  <div class="upload-box">Photo 3<input type="file" id="p3" name="p3"></div>
</div>

<div class="buttons">
  <button type="submit" class="submit">Submit</button>
  <button type="button" class="cancel" onclick="form.reset()">Cancel</button>
</div>

</form>
</div>
</div>

</body>
</html>