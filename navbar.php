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
    object-fit: contain;
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

  /* CLEAN THIN ARROW */
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

<div class="navbar-wrap">
  <div class="navbar">
    <div class="brand">
      <div class="brand-logo">
        <img src="/PR Project\Iiitdmj_logo.jpg" alt="College Logo">
      </div>
      <div class="brand-text">
        <div class="brand-title">PDPM IIITDMJ Entry–Exit Portal</div>
        <div class="brand-subtitle">Student Monitoring System</div>
      </div>
    </div>

    <div class="nav-center">
      <nav class="nav-links">
        <a href="/PR Project/Home/index.php" class="active">
          Dashboard
        </a>

        <a href="/PR Project/logs/role_login.php">
          Logs
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