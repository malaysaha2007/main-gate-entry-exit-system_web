<?php session_start(); ?>
<?php include '../navbar.php'; ?> <!-- ✅ Added navbar -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>System Login | PDPM IIITDMJ</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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

/* 🔹 HEADER (SAME AS STUDENT) */
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

/* 🔹 LOGIN BOX */
.card {
  margin: 70px auto 0;
  width: 420px;
  padding: 26px;
  border-radius: var(--radius-lg);
  background: var(--bg-card);
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
}

h2 {
  text-align: center;
  color: var(--accent-secondary);
  margin-bottom: 20px;
}

/* 🔹 INPUTS */
select, input {
  width: 100%;
  padding: 11px;
  margin-bottom: 14px;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-light);
  background: var(--bg-input);
  color: var(--text-primary);
}

/* 🔹 BUTTON */
button {
  width: 100%;
  padding: 11px;
  border: none;
  border-radius: var(--radius-md);
  background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
  color: var(--text-dark);
  font-weight: 700;
  cursor: pointer;
}

/* 🔹 HIDDEN */
.hidden {
  display: none;
}

/* 🔹 FOOTER */
footer {
  text-align: center;
  margin-top: 50px;
  font-size: 14px;
  color: var(--text-muted);
}
</style>

<script>
function toggleOptions(){
  const type = document.getElementById("login_type").value;
  document.getElementById("admin_section").classList.toggle("hidden", type !== "Admin");
  document.getElementById("hostel_section").classList.toggle("hidden", type !== "Hostel");
}
</script>
</head>

<body>

<!-- 🔹 HEADER -->
<div class="page-top">
  <div class="header-left">
    <img src="activity_logs_logo.jpg" alt="logo">
  </div>

  <div class="header-center portal-name">
    <h1>System Portal</h1>
    <p>Unified Access for Admin & Hostel Staff</p>
  </div>
</div>

<!-- 🔹 LOGIN CARD -->
<div class="card">
  <h2>System Login</h2>

  <form method="POST" action="authenticate.php">

    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="login_type" id="login_type" onchange="toggleOptions()" required>
      <option value="">Select Login Type</option>
      <option value="Admin">Admin</option>
      <option value="Hostel">Hostel Staff</option>
    </select>

    <div id="admin_section" class="hidden">
      <select name="admin_role">
        <option value="">Select Admin Role</option>
        <option value="Director">Director</option>
        <option value="Dean Academic">Dean Academic</option>
        <option value="Main Gate Guard">Main Gate Guard</option>
      </select>
    </div>

    <div id="hostel_section" class="hidden">
      <select name="hostel">
        <option value="">Select Hostel</option>
        <option value="Hostel 1">Hostel 1</option>
        <option value="Hostel 2">Hostel 2</option>
        <option value="Hostel 3">Hostel 3</option>
        <option value="Hostel 4">Hostel 4</option>
        <option value="Hostel 5">Hostel 5</option>
        <option value="Hostel 6">Hostel 6</option>
        <option value="Hostel 7">Hostel 7</option>
      </select>

      <select name="hostel_role">
        <option value="">Select Hostel Role</option>
        <option value="Warden">Warden</option>
        <option value="Caretaker">Caretaker</option>
        <option value="Hostel Guard">Hostel Guard</option>
      </select>
    </div>

    <button type="submit">Login</button>
  </form>
</div>

<footer>
  <h4>&copy; 2025 System Portal. All Rights Reserved.</h4>
</footer>

</body>
</html>