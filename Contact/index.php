<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Contact Us - PDPM IIITDMJ</title>

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

/* CONTENT */
.container {
  padding: 60px 20px;
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

.contact-box {
  background: var(--bg-card);
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  padding: 40px;
  max-width: 600px;
  width: 100%;
  text-align: center;
  box-shadow: var(--shadow-main);
}

.college-logo {
  width: 110px;
  height: auto;
  margin-bottom: 25px;
  filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.3));
}

h2 { font-size: 26px; margin-bottom: 10px; color: var(--accent-secondary); }
.desc { color: var(--text-muted); margin-bottom: 30px; font-size: 15px; }

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  text-align: left;
  margin-bottom: 30px;
  border-top: 1px solid var(--border-light);
  border-bottom: 1px solid var(--border-light);
  padding: 20px 0;
}

.info-item h4 { color: var(--accent-secondary); font-size: 14px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
.info-item p { color: var(--text-muted); font-size: 14px; line-height: 1.5; }

/* BUTTON */
.btn-college {
  display: inline-block;
  background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
  color: var(--text-dark);
  padding: 12px 28px;
  border-radius: var(--radius-md);
  text-decoration: none;
  font-weight: 700;
  transition: transform 0.2s, opacity 0.2s;
}

.btn-college:hover {
  opacity: 0.9;
  transform: translateY(-2px);
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
      src="/PR Project/Logo.jpg"
      style="height:90px; width:90px; border-radius:50%; background:#fff; padding:5px; object-fit:contain;"
      alt="logo"
    />
  </div>

  <div class="header-center portal-name">
    <h1>Support Portal</h1>
    <p>Helpdesk & Administration</p>
  </div>

  <div class="header-right">
    <nav>
      <a href="/PR Project/Home/index.php">Home</a>
    </nav>
  </div>
</header>

<div class="container">
  <div class="contact-box">
    <img src="Iiitdmj-logo.jpg" alt="IIITDMJ Logo" class="college-logo">

    <h2>Hostel Administration</h2>
    <p class="desc">For technical support regarding the SGMS (Smart Gate Monitoring System) or portal access, please contact the administration.</p>

    <div class="info-grid">
      <div class="info-item">
        <h4>📍 Location</h4>
        <p>PDPM IIITDM Jabalpur<br>Khamaria,Dumna Airport Road<br>Madhya Pradesh-482005</p>
      </div>
      <div class="info-item">
        <h4>📞 Contact</h4>
        <p>Contact Number: 0761279 4441<br>Email ID: medoffice@iiitdmj.ac.in</p>
      </div>
    </div>

    <a href="https://www.iiitdmj.ac.in/" target="_blank" class="btn-college">
      Visit Official Website
    </a>
  </div>
</div>

<footer>
  <h4>&copy; 2025 PDPM Indian Institute of Information Technology, Design and Manufacturing, Jabalpur</h4>
</footer>

</body>
</html>