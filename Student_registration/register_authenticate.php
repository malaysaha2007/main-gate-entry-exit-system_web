<?php
session_start();
require_once("../db.php");

/* -----------------------------
   BLOCK DIRECT ACCESS
----------------------------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Unauthorized access");
}

/* -----------------------------
   READ INPUT
----------------------------- */
$username  = trim($_POST["username"] ?? "");
$password  = trim($_POST["password"] ?? "");
$loginType = $_POST["login_type"] ?? "";

if ($username === "" || $password === "" || $loginType === "") {
    die("Missing credentials");
}

/* -----------------------------
   ADMIN BLOCKED
----------------------------- */
if ($loginType === "Admin") {
    die("Admins are not allowed to register students.");
}

/* -----------------------------
   HOSTEL STAFF LOGIN
----------------------------- */
if ($loginType === "Hostel") {

    $hostel = $_POST["hostel"] ?? "";
    $role   = $_POST["hostel_role"] ?? "";

    if ($hostel === "" || $role === "") {
        die("Hostel or role not selected");
    }

    /* ✅ Only allowed roles */
    $allowedRoles = ["Warden", "Caretaker", "Hostel Guard"];

    if (!in_array($role, $allowedRoles, true)) {
        die("Invalid role for registration");
    }

    $staff = $db->hostel_staff->findOne([
        "username" => $username,
        "password" => $password,
        "role"     => $role,
        "hostel"   => $hostel,
        "status"   => "ACTIVE"
    ]);

    if (!$staff) {
        die("Invalid Hostel Staff Credentials");
    }

    /* -----------------------------
       SET SESSION (REGISTRATION)
    ----------------------------- */
    $_SESSION["logged_in"] = true;
    $_SESSION["section"]   = "registration";   // 🔒 module lock
    $_SESSION["user_type"] = "Hostel";
    $_SESSION["username"]  = $staff["username"];
    $_SESSION["role"]      = $staff["role"];
    $_SESSION["hostel"]    = $staff["hostel"]; // 🔒 hostel lock

    header("Location: student_registration.php");
    exit;
}

die("Invalid login type");
