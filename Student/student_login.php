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

.header-left {
  flex: 0 0 auto;
}


.header-left img {
 width: 50px;
  height: 50px;
  border-radius: 8px;
  overflow: hidden;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
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
  font-size: 14px;
}

.login-box input::placeholder {
  color: var(--text-muted);
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
  background: linear-gradient(
    90deg,
    var(--accent-primary),
    var(--accent-secondary)
  );
  color: var(--text-dark);
}

.login-box button.login:hover {
  opacity: 0.9;
}

.login-box button.reset {
  background: transparent;
  color: var(--danger);
  border: 1px solid var(--danger);
  margin-right: 10px;
}

.login-box button.reset:hover {
  background: rgba(239,68,68,0.15);
}

/* Footer */
footer {
  text-align: center;
  margin-top: 50px;
  font-size: 14px;
  color: var(--text-muted);
}

/* Responsive */
@media (max-width: 768px) {
  .page-top {
    flex-direction: column;
    align-items: flex-start;
    padding: 16px 16px 0;
  }

  .header-center {
    padding-top: 0;
  }

  .portal-name h1 {
    font-size: 32px;
  }

  .portal-name p {
    font-size: 14px;
  }

  .login-box {
    width: calc(100% - 32px);
    margin-top: 40px;
  }

 
}
</style>
</head>

<body>

<div class="page-top">
  <div class="header-left">
    <img
      src="/PR Project/student_logo.png"
      alt="logo"
    />
  </div>

  <div class="header-center portal-name">
    <h1>Student Portal</h1>
    <p>Access Your Profile & Activity Logs</p>
  </div>
</div>

<div class="login-box">
  <h2>Student Login</h2>

  <form action="student_login_backend.php" method="post" autocomplete="off">
    <label for="studentid">Student ID</label>
    <input
      type="text"
      id="studentid"
      name="studentid"
      required
      placeholder="e.g. S1001"
    />

    <label for="password">Password</label>
    <input
      type="password"
      id="password"
      name="password"
      required
      placeholder="Your password"
    />

    <div class="actions">
      <button type="reset" class="reset">Reset</button>
      <button type="submit" class="login">Login</button>
    </div>
  </form>
</div>

<footer>
  <h4>&copy; 2025 Student Portal. All Rights Reserved.</h4>
</footer>

</body>
</html>