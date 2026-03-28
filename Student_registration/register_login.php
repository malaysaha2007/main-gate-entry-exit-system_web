<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Registration Login | PDPM IIITDMJ</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box;font-family:"Segoe UI",sans-serif;}
body{
  margin:0;min-height:100vh;
  background:linear-gradient(135deg,#020617,#020b2d,#020617);
  display:flex;justify-content:center;align-items:center;
  color:#e5e7eb;
}
.card{
  background:rgba(15,23,42,0.95);
  width:420px;padding:35px;border-radius:18px;
  box-shadow:0 25px 60px rgba(0,0,0,.6);
}
h2{text-align:center;color:#22d3ee;margin-bottom:25px;}
select,input{
  width:100%;padding:12px;margin-bottom:15px;
  border-radius:10px;border:1px solid #1e293b;
  background:#020617;color:white;
}
button{
  width:100%;padding:12px;border:none;border-radius:12px;
  background:linear-gradient(90deg,#2563eb,#06b6d4);
  color:white;font-size:16px;cursor:pointer;
}
.hidden{display:none;}
</style>

<script>
function toggleOptions(){
  const type=document.getElementById("login_type").value;
  document.getElementById("admin_section").classList.toggle("hidden",type!=="Admin");
  document.getElementById("hostel_section").classList.toggle("hidden",type!=="Hostel");
}
</script>
</head>

<body>
<div class="card">
<h2>Student Registration Login</h2>

<form method="POST" action="\PR Project\Student_registration\register_authenticate.php">

<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>

<select name="login_type" id="login_type" onchange="toggleOptions()" required>
  <option value="">Select Login Type</option>
  <option value="Admin">Admin</option>
  <option value="Hostel">Hostel Staff</option>
</select>

<!-- ADMIN OPTIONS -->
<div id="admin_section" class="hidden">
  <select name="admin_role">
    <option value="">Select Admin Role</option>
    <option value="Director">Director</option>
    <option value="Dean Academic">Dean Academic</option>
    <option value="Main Gate Guard">Main Gate Guard</option>
  </select>
</div>

<!-- HOSTEL OPTIONS -->
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
</body>
</html>
