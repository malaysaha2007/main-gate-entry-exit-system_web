<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Hostel Rules - PDPM IIITDMJ</title>

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

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: Inter, system-ui, Arial, sans-serif;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Header matched to other portals */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 40px;
  position: relative;
  border-bottom: 1px solid var(--border-light);
}

.header-left, .header-right { flex: 1; }

.header-center {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
}

.portal-name h1 {
  font-size: 36px;
  margin: 0;
  color: var(--text-primary);
}

.portal-name p {
  margin: 6px 0 0;
  font-size: 16px;
  color: var(--text-muted);
}

nav { text-align: right; }

nav a {
  text-decoration: none;
  font-weight: 700;
  padding: 10px 18px;
  border-radius: var(--radius-md);
  background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
  color: var(--text-dark);
}

nav a:hover { opacity: 0.9; }

/* PAGE CONTENT */
.container {
  padding: 60px 20px;
  max-width: 900px;
  margin: 0 auto;
  flex: 1;
}

.section-title { font-size: 32px; text-align: center; margin-bottom: 8px; color: var(--accent-secondary); }
.section-desc { text-align: center; color: var(--text-muted); margin-bottom: 50px; }

/* RULE CARDS */
.rules-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.rule-card {
  background: var(--bg-card);
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  padding: 30px;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.rule-card:hover {
  transform: translateX(10px);
  border-color: var(--accent-primary);
  box-shadow: var(--shadow-main);
}

.rule-number {
  font-size: 48px;
  font-weight: 900;
  color: rgba(59, 130, 246, 0.1);
  margin-right: 30px;
  min-width: 60px;
}

.rule-content h3 {
  font-size: 20px;
  margin-bottom: 10px;
  color: var(--accent-secondary);
}

.rule-content p {
  color: var(--text-muted);
  font-size: 15px;
  line-height: 1.6;
}

.highlight-time {
  color: var(--danger);
  font-weight: bold;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 40px 20px;
  font-size: 14px;
  color: var(--text-muted);
  border-top: 1px solid var(--border-light);
}
</style>
</head>

<body>

<header>
  <div class="header-left">
    <img
      src="../rules_logo.jpg"
      style="height:90px; width:90px; border-radius:50%; background:#fff; padding:5px; object-fit:contain;"
      alt="logo"
    />
  </div>

  <div class="header-center portal-name">
    <h1>Rules & Regulations</h1>
    <p class="section-desc">Please adhere to the following guidelines for a seamless hostel experience.</p>
  </div>

  <div class="header-right">
    <nav>
      <a href="../Home/index.php">Home</a>
    </nav>
  </div>
</header>

<div class="container">

  <div class="rules-grid">
    
    <div class="rule-card">
      <div class="rule-number">01</div>
      <div class="rule-content">
        <h3>Night Curfew Policy</h3>
        <p>
          The hostel main gate closes strictly at <span class="highlight-time">10:30 PM</span>. 
          Any student attempting to enter or exit after this time will be flagged by the automated system, 
          and an alert will be sent to the warden immediately.
        </p>
      </div>
    </div>

    <div class="rule-card">
      <div class="rule-number">02</div>
      <div class="rule-content">
        <h3>Face Recognition Entry</h3>
        <p>
          Entry and exit are permitted <strong>only</strong> via the Face Recognition System. 
          Ensure your face is clearly visible (remove masks/helmets) when standing in front of the kiosk.
        </p>
      </div>
    </div>

    <div class="rule-card">
      <div class="rule-number">03</div>
      <div class="rule-content">
        <h3>Access Protocol</h3>
        <p>
          All students must log exit and entry through the portal to maintain an accurate real-time record of campus presence and ensure student safety at all times.
        </p>
      </div>
    </div>

    <div class="rule-card">
      <div class="rule-number">04</div>
      <div class="rule-content">
        <h3>Integrity Policy</h3>
        <p>
          Providing false information on the digital portal to bypass security protocols may lead to disciplinary action, including fines or suspension.
        </p>
      </div>
    </div>

  </div>
</div>

<footer>
  <h4>&copy; 2025 PDPM Indian Institute of Information Technology, Design and Manufacturing, Jabalpur</h4>
</footer>

</body>
</html>