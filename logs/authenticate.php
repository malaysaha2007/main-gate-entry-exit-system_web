<?php
session_start();
require_once("../db.php");

/* ---------------- BASIC VALIDATION ---------------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$loginType = $_POST["login_type"] ?? "";
$username  = trim($_POST["username"] ?? "");
$password  = trim($_POST["password"] ?? "");

if ($loginType === "" || $username === "" || $password === "") {
    die("Missing credentials");
}

/* ---------------- ADMIN LOGIN ---------------- */
if ($loginType === "Admin") {

    $admin = $db->admins->findOne([
        "username" => $username,
        "password" => $password
    ]);

    if (!$admin) {
        die("Invalid Admin Credentials");
    }

    // Secure session
    $_SESSION = [];
    $_SESSION["auth"]        = true;
    $_SESSION["module"]     = "LOGS";
    $_SESSION["login_type"] = "Admin";
    $_SESSION["user_id"]    = (string)$admin->_id;
    $_SESSION["username"]   = $admin->username;
    $_SESSION["role"]       = $admin->role;
    $_SESSION["hostel"]     = null;

    header("Location: view_logs.php");
    exit;
}

/* ---------------- HOSTEL LOGIN ---------------- */
if ($loginType === "Hostel") {

    $hostel = $_POST["hostel"] ?? "";

    if ($hostel === "") {
        die("Hostel is required");
    }

    $staff = $db->hostel_staff->findOne([
        "username" => $username,
        "password" => $password,
        "hostel"   => $hostel,
        "status"   => "ACTIVE"
    ]);

    if (!$staff) {
        die("Invalid Hostel Staff Credentials");
    }

    // Secure session
    $_SESSION = [];
    $_SESSION["auth"]        = true;
    $_SESSION["module"]     = "LOGS";
    $_SESSION["login_type"] = "Hostel";
    $_SESSION["user_id"]    = (string)$staff->_id;
    $_SESSION["username"]   = $staff->username;
    $_SESSION["role"]       = $staff->role;
    $_SESSION["hostel"]     = $staff->hostel;

    header("Location: view_logs.php");
    exit;
}

/* ---------------- FALLBACK ---------------- */
die("Invalid login type");
