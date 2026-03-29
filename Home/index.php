<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PDPM IIITDMJ | Student Entry–Exit Portal</title>

  <style>
    :root {
      --bg-main: #071126;
      --bg-card: #121b33;
      --primary: #3b82f6;
      --secondary: #06b6d4;
      --text-main: #e6eef8;
      --text-muted: #9aa8c7;
      --border: rgba(255, 255, 255, 0.12);
      --nav-bg: #081126;
      --nav-btn: #10213f;
      --nav-btn-hover: #13284a;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
      background: linear-gradient(180deg, #050c1d, var(--bg-main));
      color: var(--text-main);
      line-height: 1.6;
    }

    .navbar-wrap {
      width: 100%;
      background: var(--nav-bg);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .navbar {
      width: 100%;
      padding: 14px 24px;
      display: grid;
      grid-template-columns: auto 1fr auto;
      align-items: center;
      gap: 18px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
      justify-self: start;
    }

 .brand-logo {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  overflow: hidden;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* keeps logo proper */
}

    .brand-text {
      min-width: 0;
    }

    .brand-title {
      font-size: 20px;
      font-weight: 700;
      color: #3b82f6;
      line-height: 1.15;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .brand-subtitle {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.2;
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

   .nav-center {
  justify-content: center;
  transform: translateX(-80px);
}

    .nav-links {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .nav-links a {
      color: #d6dfef;
      text-decoration: none;
      font-size: 16px;
       font-weight: 600;
         color: #e2e8f0;

      padding: 8px 16px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
      background: var(--nav-btn);
      border: 1px solid rgba(255, 255, 255, 0.10);
      transition: all 0.2s ease;
    }

    .nav-links a:hover {
      background: var(--nav-btn-hover);
      border-color: rgba(255, 255, 255, 0.16);
      transform: translateY(-1px);
    }

    .nav-links a.active {
      background: var(--nav-btn-hover);
      border-color: rgba(255, 255, 255, 0.16);
      color: #ffffff;
    }

    .nav-icon {
      width: 16px;
      height: 16px;
      display: inline-block;
      flex: 0 0 auto;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
      opacity: 0.95;
    }

    .hero {
      padding: 80px 40px;
      text-align: center;
    }

    .hero h1 {
      font-size: 44px;
      margin-bottom: 16px;
    }

    .hero p {
      font-size: 18px;
      color: var(--text-muted);
      max-width: 750px;
      margin: auto;
    }

    section {
      padding: 70px 40px;
    }

    .section-title {
      text-align: center;
      font-size: 32px;
      margin-bottom: 14px;
    }

    .section-desc {
      text-align: center;
      color: var(--text-muted);
      margin-bottom: 48px;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
      max-width: 1100px;
      margin: auto;
    }

    .card {
      background: var(--bg-card);
      padding: 28px;
      border-radius: 14px;
      border: 1px solid var(--border);
    }

    .card h3 {
      margin-bottom: 10px;
      color: var(--secondary);
    }

    .card p {
      color: var(--text-muted);
      font-size: 15px;
    }

    .summary-box {
      max-width: 900px;
      margin: auto;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 30px;
    }

    .summary-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .timestamp {
      font-size: 14px;
      color: var(--text-muted);
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 18px;
    }

    .summary-item {
      padding: 18px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--border);
      text-align: center;
    }

    .summary-item h3 {
      font-size: 26px;
      color: var(--primary);
      margin-bottom: 6px;
    }

    .summary-item p {
      font-size: 14px;
      color: var(--text-muted);
    }

    .rules {
      max-width: 900px;
      margin: auto;
    }

    .rules ul {
      list-style: none;
    }

    .rules li {
      margin-bottom: 12px;
      padding-left: 16px;
      position: relative;
      color: var(--text-muted);
    }

    .rules li::before {
      content: "•";
      position: absolute;
      left: 0;
      color: var(--primary);
    }

    footer {
      border-top: 1px solid var(--border);
      padding: 30px 40px;
      text-align: center;
      font-size: 14px;
      color: var(--text-muted);
    }

    @media (max-width: 1100px) {
      .navbar {
        grid-template-columns: 1fr;
        justify-items: start;
      }

      .nav-center {
        width: 100%;
        justify-self: center;
      }

      .brand {
        justify-self: start;
      }
    }

    @media (max-width: 900px) {
      .hero h1 {
        font-size: 32px;
      }

      .hero p {
        font-size: 16px;
      }

      .navbar {
        padding: 12px 14px;
      }

      .nav-links {
        gap: 8px;
      }

 
    }
    /* DROPDOWN */
.dropdown {
  position: relative;
}

.dropdown > a {
  cursor: pointer;
}

/* HIDDEN MENU */
.dropdown-menu {
  position: absolute;
  top: 110%;
  left: 0;
  background: #10213f;
  border-radius: 10px;
  padding: 8px 0;
  min-width: 180px;
  display: none;
  flex-direction: column;
  border: 1px solid rgba(255,255,255,0.1);
  z-index: 1000;
}

/* ITEMS */
.dropdown-menu a {
  padding: 10px 16px;
  font-size: 14px;
  color: #d6dfef;
  text-decoration: none;
  display: block;
  white-space: nowrap;
}
/* DROPDOWN MENU */
.dropdown {
  position: relative;
}

/* MENU HIDDEN BY DEFAULT */
.dropdown-menu {
  position: absolute;
  top: 110%;
  left: 0;
  background: #10213f;
  border-radius: 10px;
  padding: 6px 0;
  min-width: 180px;
  display: none;
  flex-direction: column;
  border: 1px solid rgba(255,255,255,0.1);
  z-index: 1000;
}

/* DROPDOWN ITEMS */
.dropdown-menu a {
  padding: 10px 16px;
  font-size: 14px;
  color: #d6dfef;
  text-decoration: none;
  display: block;
  white-space: nowrap;
}

/* HOVER EFFECT */
.dropdown-menu a:hover {
  background: #13284a;
}

/* SHOW MENU ON HOVER */
.dropdown:hover .dropdown-menu {
  display: flex;
}

/* ===== CLEAN THIN ARROW ===== */
.arrow {
  display: inline-block;
  width: 6px;
  height: 6px;
  margin-left: 6px;

  border-right: 2px solid #cbd5e1;
  border-bottom: 2px solid #cbd5e1;

  transform: rotate(45deg);
  transition: transform 0.2s ease;
}

/* ROTATE ARROW ON HOVER */
.dropdown:hover .arrow {
  transform: rotate(225deg);
}
  </style>
</head>

<body>

  <div class="navbar-wrap">
    <div class="navbar">
      <div class="brand">
<div class="brand-logo">
  <img src="/PR Project\Iiitdmj_logo.jpg" alt="College Logo">
</div>        <div class="brand-text">
          <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
          <div class="brand-subtitle">Student Monitoring System</div>
        </div>
      </div>

      <div class="nav-center">
        <nav class="nav-links">
          <a href="/PR Project/Home/index.php" class="active">
            Home
          </a>

          <a href="/PR Project/logs/role_login.php">
            Activity Logs
          </a>
<div class="dropdown">
  <a href="#">Login <span class="arrow"></span>
  <div class="dropdown-menu">
    <a href="/PR Project/Student/student_login.php">Student Login</a>
    <a href="/PR Project/Hostel/login.php">Hostel Login</a>
    <a href="/PR Project/Admin/login.php">Admin Login</a>
  </div>
</div>

          <a href="/PR Project/Rules/index.php">
            Rules
          </a>

          <a href="/PR Project/camera.php">
            <span style="font-size:15px;">📷</span> Camera
          </a>
        </nav>
      </div>
    </div>
  </div>

  <div class="hero">
    <h1>PDPM IIITDMJ Student Entry–Exit Management System</h1>
    <p>
      A secure digital platform to record, monitor, and manage student movement
      across the PDPM IIITDMJ campus with real-time access for administration and guards.
    </p>
  </div>

  <section>
    <h2 class="section-title">Summary</h2>
    <p class="section-desc">Quick overview of current entry–exit status</p>

    <div class="summary-box">
      <div class="summary-header">
        <strong>Quick Stats</strong>
        <div class="timestamp" id="currentTime"></div>
      </div>

      <div class="summary-grid">
        <div class="summary-item">
          <h3>2</h3>
          <p>Total Records</p>
        </div>

        <div class="summary-item">
          <h3>2</h3>
          <p>Inside (IN)</p>
        </div>

        <div class="summary-item">
          <h3>0</h3>
          <p>Outside (OUT)</p>
        </div>

        <div class="summary-item">
          <h3>7</h3>
          <p>Hostels</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <h2 class="section-title">About the System</h2>
    <p class="section-desc">
      A centralized digital solution for PDPM IIITDMJ to ensure campus safety and accountability.
    </p>

    <div class="card-grid">
      <div class="card">
        <h3>Digital Logging</h3>
        <p>Automatic date and time–stamped entry–exit records for every student.</p>
      </div>

      <div class="card">
        <h3>Role-Based Access</h3>
        <p>Separate dashboards for students, administration, wardens, and guards.</p>
      </div>

      <div class="card">
        <h3>Enhanced Campus Monitoring</h3>
        <p>Guards can verify student movement at campus gates.</p>
      </div>
    </div>
  </section>

  <section>
    <h2 class="section-title">Basic Rules & Guidelines</h2>

    <div class="rules">
      <ul>
        <li>All students must log exit and entry through the portal.</li>
        <li>Guards will verify records at entry and exit points.</li>
        <li>Providing false information may lead to disciplinary action.</li>
        <li>Emergency movements must be reported to wardens or guards.</li>
        <li>Portal access is restricted to authorized PDPM IIITDMJ personnel only.</li>
      </ul>
    </div>
  </section>

  <footer>
    © 2025 PDPM Indian Institute of Information Technology, Design and Manufacturing, Jabalpur
    <br />
    Student Entry–Exit Management System | Campus Safety Initiative
  </footer>

  <script>
    function updateTime() {
      const now = new Date();
      const pad = n => n.toString().padStart(2, '0');

      const date =
        pad(now.getDate()) + '-' +
        pad(now.getMonth() + 1) + '-' +
        now.getFullYear();

      const time =
        pad(now.getHours()) + ':' +
        pad(now.getMinutes()) + ':' +
        pad(now.getSeconds());

      document.getElementById('currentTime').textContent =
        date + ' ' + time;
    }

    updateTime();
    setInterval(updateTime, 1000);
  </script>

  <script>
    let cameraStream = null;

    function openCamera() {
      const overlay = document.getElementById('cameraOverlay');
      const video = document.getElementById('cameraVideo');

      overlay.style.display = 'block';

      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          cameraStream = stream;
          video.srcObject = stream;
        })
        .catch(err => {
          alert("Camera access denied or not available");
          overlay.style.display = 'none';
        });
    }

    function closeCamera() {
      const overlay = document.getElementById('cameraOverlay');

      if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
      }

      overlay.style.display = 'none';
    }
  </script>

  <div id="cameraOverlay" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#000;
    z-index:9999;
  ">
    <button onclick="closeCamera()" style="
      position:absolute;
      top:20px;
      right:20px;
      font-size:28px;
      background:none;
      border:none;
      color:white;
      cursor:pointer;
      z-index:10000;
    ">❌</button>

    <video id="cameraVideo" autoplay playsinline style="
      width:100%;
      height:100%;
      object-fit:cover;
    "></video>
  </div>

</body>
</html>