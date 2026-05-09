<?php session_start(); ?>

<?php include '../navbar.php'; ?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Portal - Login</title>

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

  --danger: #ef4444;
  --border-light: rgba(255,255,255,0.08);

  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 14px;

  --shadow-main: 0 8px 28px rgba(119,202,202,0.4);
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  min-height: 100vh;
  font-family: Inter, system-ui, Arial, sans-serif;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
}

/* Page top area */
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

/* Login Box */
.login-box {
  margin: 70px auto 0;
  width: 420px;
  padding: 26px;
  background: var(--bg-card);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
}

.login-box h2 {
  text-align: center;
  margin-bottom: 18px;
  color: var(--accent-secondary);
}

.login-box label {
  display: block;
  margin: 10px 0 4px;
  font-size: 14px;
  color: var(--text-muted);
}

.login-box input {
  width: 100%;
  padding: 11px;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-light);
  background: var(--bg-input);
  color: var(--text-primary);
}

.actions {
  text-align: center;
  margin-top: 18px;
}

.login-box button {
  padding: 11px 18px;
  border-radius: var(--radius-md);
  font-size: 14px;
  font-weight: 700;
  border: none;
  cursor: pointer;
}

.login-box button.login {
  background: linear-gradient(90deg,var(--accent-primary),var(--accent-secondary));
  color: var(--text-dark);
}

.login-box button.reset {
  background: transparent;
  color: var(--danger);
  border: 1px solid var(--danger);
  margin-right: 10px;
}

/* 🔥 NEW SIGNUP BUTTON */
.signup-link {
  text-align: center;
  margin-top: 18px;
}

.signup-link a {
  display: inline-block;
  padding: 10px 16px;
  border-radius: var(--radius-md);
  border: 1px solid var(--accent-primary);
  color: var(--accent-primary);
  text-decoration: none;
  font-weight: 600;
  transition: 0.2s;
}

.signup-link a:hover {
  background: rgba(59,130,246,0.15);
}

/* Footer */
footer {
  text-align: center;
  margin-top: 50px;
  font-size: 14px;
  color: var(--text-muted);
}
</style>

</head>

<body>

<div class="page-top">
  <div class="header-left">
    <img src="../student_logo.png" alt="logo"/>
  </div>

  <div class="header-center portal-name">
    <h1>Student Portal</h1>
    <p>Access Your Profile & Activity Logs</p>
  </div>
</div>

<div class="login-box">
  <h2>Student Login</h2>

  <form action="student_login_backend.php" method="post" autocomplete="off">
    <label>Student ID</label>
    <input type="text" name="studentid" required placeholder="e.g. 24BCS137"/>


<label>Password</label>
<input type="password" name="password" required placeholder="Your password"/>

<div class="actions">
  <button type="reset" class="reset">Reset</button>
  <button type="submit" class="login">Login</button>
</div>


  </form>

  <!-- 🔥 NEW BUTTON -->

  <div class="signup-link">
    <a href="student_signup.php">Sign Up for New Student</a>
  </div>

</div>

<footer>
  <h4>&copy; 2025 Student Portal. All Rights Reserved.</h4>
</footer>

</body>
</html>
