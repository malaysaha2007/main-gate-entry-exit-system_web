<?php
session_start();
require '../db.php';

$admins = $db->admins;

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $level    = trim($_POST['level']);

    $user = $admins->findOne([
        "username" => $username
    ]);

    if (!$user) {
        $error = "User not found.";
    }
    else {
        if ($user['password'] !== $password) {
            $error = "Incorrect password.";
        }
        else {

            $dbRole = $user['role'] ?? null;

            $roleMap = [
                "Director"        => "Director",
                "Dean Academic"   => "Dean Academic",
                "Main Gate Guard" => "Guard"
            ];

            if (!isset($roleMap[$dbRole])) {
                $error = "Invalid role configuration.";
            }
            elseif ($roleMap[$dbRole] !== $level) {
                $error = "Incorrect access level selected.";
            }
            else {

                $_SESSION['admin'] = [
                    'id'        => (string)$user['_id'],
                    'admin_id'  => $user['admin_id'],
                    'username'  => $user['username'],
                    'name'      => $user['name'],
                    'role'      => $dbRole,
                    'scope'     => $user['scope'],
                    'level'     => $level
                ];

                header("Location: Admin/dashboard.php");
                exit;
            }
        }
    }
}
?>

<?php include '../navbar.php'; ?> <!-- ✅ MOVED HERE -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administration Login</title>

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

  --radius-md: 10px;
  --radius-lg: 14px;

  --shadow-main: 0 8px 28px rgba(119,202,202,0.4);
}

* { box-sizing: border-box; }

body {
  margin: 0;
  min-height: 100vh;
  font-family: Inter, system-ui, Arial, sans-serif;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
}

/* HEADER */
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

.portal-name h1 {
  font-size: 25px;
  margin: 0;
}

.portal-name p {
  margin: 6px 0 0;
  font-size: 12px;
  color: var(--text-muted);
}

/* LOGIN BOX */
.login-container {
  margin: 70px auto 0;
  width: 420px;
  padding: 26px;
  background: var(--bg-card);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
}

.login-container h2 {
  text-align: center;
  margin-bottom: 18px;
  color: var(--accent-secondary);
}

.error {
  background: rgba(239,68,68,0.15);
  color: var(--danger);
  padding: 10px;
  border-radius: 6px;
  text-align: center;
  margin-bottom: 14px;
}

.login-container input,
.login-container select {
  width: 100%;
  padding: 11px;
  margin-bottom: 14px;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-light);
  background: var(--bg-input);
  color: var(--text-primary);
}

.login-container button {
  width: 100%;
  padding: 11px;
  font-weight: 700;
  border-radius: var(--radius-md);
  border: none;
  cursor: pointer;
  background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
  color: var(--text-dark);
}

/* FOOTER */
footer {
  text-align: center;
  margin-top: 50px;
  font-size: 14px;
  color: var(--text-muted);
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="page-top">
  <div class="header-left">
    <img src="../student_logo.png" alt="logo">
  </div>

  <div class="header-center portal-name">
    <h1>Administration Portal</h1>
    <p>Manage Students, Logs & Curfew System</p>
  </div>
</div>

<!-- LOGIN -->
<div class="login-container">
  <h2>Administration Login</h2>

  <?php if (isset($error)) { ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php } ?>

  <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="level" required>
      <option value="">Select Access Level</option>
      <option value="Director">Director</option>
      <option value="Dean Academic">Dean Academic</option>
      <option value="Guard">Guard</option>
    </select>

    <button type="submit" name="login">Login</button>
  </form>
</div>

<footer>
  <h4>&copy; 2025 Administration Portal. All Rights Reserved.</h4>
</footer>

</body>
</html>