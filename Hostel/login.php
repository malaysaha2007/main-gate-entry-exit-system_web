<?php
session_start();
require '../db.php';

$username = '';
$hostel   = '';
$role     = '';

if (isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $hostel   = $_POST['hostel'] ?? '';
    $role     = $_POST['role'] ?? '';

    $user = $db->hostel_staff->findOne([
        'username' => $username,
        'password' => $password,
        'hostel'   => $hostel,
        'role'     => $role,
        'status'   => 'ACTIVE'
    ]);

    if (!$user) {
        $error = "Invalid hostel staff credentials.";
    } else {

        $_SESSION['user'] = [
            'username' => $user['username'],
            'role'     => $user['role'],
            'hostel'   => $user['hostel'],
            'type'     => 'HOSTEL_STAFF'
        ];

        header("Location: view_students.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hostel Login</title>

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
  --danger: #ef4444;
  --border-light: rgba(255,255,255,0.08);
  --radius-md: 10px;
  --shadow-main: 0 8px 28px rgba(119,202,202,0.4);
}

* { box-sizing: border-box; }

body {
  margin: 0;
  min-height: 100vh;
  font-family: system-ui, Arial, sans-serif;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
}

/* 🔹 STUDENT PORTAL TOP SECTION */
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

/* LOGIN BOX */
.login-container {
  width: 400px;
  padding: 28px;
  background: var(--bg-card);
  border-radius: 14px;
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
  margin: 50px auto;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
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

input, select {
  width: 100%;
  padding: 11px;
  margin-bottom: 14px;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-light);
  background: var(--bg-input);
  color: var(--text-primary);
}

button {
  width: 100%;
  padding: 11px;
  font-weight: 700;
  border-radius: var(--radius-md);
  border: none;
  cursor: pointer;
  background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
}
</style>
</head>

<body>

<!-- ✅ NAVBAR -->
<?php include '../navbar.php'; ?>

<!-- ✅ TOP SECTION -->
<div class="page-top">
  <div class="header-left">
    <img src="../hostel_logo.jpg" alt="logo">
  </div>

  <div class="header-center portal-name">
    <h1>Hostel Portal</h1>
    <p>Access Student Records & Monitoring</p>
  </div>
</div>

<!-- ✅ LOGIN BOX -->
<div class="login-container">
  <h2>Hostel Login</h2>

  <?php if (isset($error)) : ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">

    <input type="text" name="username" placeholder="Username" required
           value="<?php echo htmlspecialchars($username); ?>">

    <select name="hostel" required>
      <option value="">Select Hostel</option>
      <?php for ($i = 1; $i <= 6; $i++): ?>
        <option value="Hostel <?php echo $i; ?>"
          <?php if ($hostel === "Hostel $i") echo 'selected'; ?>>
          Hostel <?php echo $i; ?>
        </option>
      <?php endfor; ?>
    </select>

    <select name="role" required>
      <option value="">Select Designation</option>
      <option value="Warden" <?php if ($role === 'Warden') echo 'selected'; ?>>Warden</option>
      <option value="Caretaker" <?php if ($role === 'Caretaker') echo 'selected'; ?>>Caretaker</option>
      <option value="Hostel Guard" <?php if ($role === 'Hostel Guard') echo 'selected'; ?>>Hostel Guard</option>
    </select>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

  </form>
</div>

</body>
</html>