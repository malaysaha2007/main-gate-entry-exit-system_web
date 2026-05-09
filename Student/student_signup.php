<?php session_start(); ?>

<?php include '../navbar.php'; ?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Sign Up</title>

<!-- Google Identity -->

<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
:root {
  --bg-main: #071126;
  --bg-secondary: #071024;
  --bg-card: #27242c;
  --accent-primary: #3b82f6;
  --accent-secondary: #06b6d4;
  --text-primary: #e6eef8;
  --text-muted: #98a6bf;
  --border-light: rgba(255,255,255,0.08);
  --radius-lg: 14px;
  --shadow-main: 0 8px 28px rgba(119,202,202,0.4);
}

body {
  margin: 0;
  font-family: Inter, system-ui, Arial;
  background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
  color: var(--text-primary);
}

/* Header */
.page-top {
  display: flex;
  align-items: flex-start;
  padding: 16px 24px 0;
  gap: 18px;
}

.header-left img {
  width: 50px;
  height: 50px;
}

.portal-name h1 {
  font-size: 25px;
  margin: 0;
}

.portal-name p {
  font-size: 12px;
  color: var(--text-muted);
}

/* Card */
.signup-box {
  margin: 80px auto;
  width: 420px;
  padding: 30px;
  background: var(--bg-card);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-main);
  text-align: center;
}

.signup-box h2 {
  margin-bottom: 20px;
  color: var(--accent-secondary);
}

/* Google Button Wrapper */
.google-btn {
  margin-top: 20px;
  display: flex;
  justify-content: center;
}
.note {
  margin-top: -20px;
  font-size: 13px;
  color: var(--text-muted);
}
.error-box {
  margin-top: 14px;
  padding: 10px;
  border-radius: 8px;
  background: rgba(239,68,68,0.15);
  color: #ef4444;
  font-size: 13px;
  border: 1px solid rgba(239,68,68,0.3);
}

.hidden {
  display: none;
}

/* Footer */
footer {
  text-align: center;
  margin-top: 50px;
  color: var(--text-muted);
}
</style>

</head>

<body>

<div class="page-top">
  <div class="header-left">
    <img src="../student_logo.png">
  </div>

  <div class="portal-name">
    <h1>Student Registration</h1>
    <p>Sign up using your institute email</p>
  </div>
</div>

<div class="signup-box">
  <h2>Sign Up with Google</h2>
  <p class="note">Note : Use college Email only.</p>

  <!-- Google Button -->

  <div id="g_id_onload"
       data-client_id="615531974414-9ejio80alq154o94asa4su4ou7iib4d1.apps.googleusercontent.com"
       data-callback="handleCredentialResponse">
  </div>

  <div class="google-btn">
    <div class="g_id_signin"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_up_with"
         data-shape="rectangular">
    </div>
  </div>
  <div id="email-error" class="error-box hidden">
  Use your institute email only (@iiitdmj.ac.in)
</div>

</div>

<footer>
  <h4>&copy; 2025 Student Portal. All Rights Reserved.</h4>
</footer>

<script>
function handleCredentialResponse(response) {
    const data = parseJwt(response.credential);

    const email = data.email;

    // 🔴 Restrict to college email
   const errorBox = document.getElementById("email-error");

// Hide previous error
errorBox.classList.add("hidden");

if (!email.endsWith("@iiitdmj.ac.in")) {
    errorBox.classList.remove("hidden");
    return;
}

    // 🔴 Send to backend
    fetch("google_signup_backend.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ token: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    });
}

/* Decode JWT */
function parseJwt(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
}
</script>

</body>
</html>
