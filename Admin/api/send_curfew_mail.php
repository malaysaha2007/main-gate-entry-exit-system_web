<?php
session_start();
require '../../db.php';
require '../../config/mail.php';

// ---------- AUTH ----------
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    exit("Unauthorized");
}

if (empty($_POST['students'])) {
    http_response_code(400);
    exit("No students selected");
}

// ---------- TIME ----------
date_default_timezone_set('Asia/Kolkata');
$timestamp = date('Y-m-d H:i:s');

// ---------- ADMIN ----------
$admin = $_SESSION['admin'];
$sentCount = 0;

// ---------- SEND ONE MAIL PER STUDENT ----------
foreach ($_POST['students'] as $json) {

    $s = json_decode($json, true);
    if (!$s || empty($s['email'])) {
        continue;
    }

    // ---------- SAFE VALUES ----------
    $name    = $s['name']    ?? '';
    $roll    = $s['roll']    ?? '';
    $hostel  = $s['hostel']  ?? '';
    $room    = $s['room']    ?? '';
    $phone   = $s['phone']   ?? '';
    $email   = $s['email']   ?? '';
    $purpose = $s['purpose'] ?? '';
    $outTime = $s['outTime'] ?? '';
    $inTime  = $s['inTime']  ?? '—';

    // ---------- MAIL ----------
    $subject = "Late Entry Notice | Hostel Office";

    $message = "
    <html>
    <head>
      <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f4f6f8;
          padding: 20px;
        }
        .card {
          background: #ffffff;
          border-radius: 6px;
          padding: 25px;
          max-width: 700px;
          margin: auto;
        }
        .header {
          background: #1e3a8a;
          color: #ffffff;
          padding: 14px;
          border-radius: 4px;
          text-align: center;
          font-size: 20px;
          font-weight: bold;
        }
        .content {
          margin-top: 20px;
          font-size: 15px;
          color: #222;
        }
        .line {
          margin: 6px 0;
        }
        .label {
          font-weight: bold;
          display: inline-block;
          width: 120px;
        }
        .footer {
          margin-top: 20px;
          font-size: 13px;
          color: #555;
        }
      </style>
    </head>

    <body>
      <div class='card'>
        <div class='header'>Hostel Late Entry Notification</div>

        <div class='content'>
          <p>Dear <b>{$name}</b>,</p>

          <p>
            Your entry into the hostel has been recorded
            <b>after the official curfew time</b>.
          </p>

          <div class='line'><span class='label'>Name :</span> {$name}</div>
          <div class='line'><span class='label'>Roll No. :</span> {$roll}</div>
          <div class='line'><span class='label'>Hostel :</span> {$hostel}</div>
          <div class='line'><span class='label'>Room :</span> {$room}</div>
          <div class='line'><span class='label'>Phone :</span> {$phone}</div>
          <div class='line'><span class='label'>Email :</span> {$email}</div>
          <div class='line'><span class='label'>Purpose :</span> {$purpose}</div>
          <div class='line'><span class='label'>Out Time :</span> {$outTime}</div>
          <div class='line'><span class='label'>In Time :</span> {$inTime}</div>

          <p style='margin-top:15px;'>
            Repeated late entries may lead to disciplinary action.
          </p>
        </div>

        <div class='footer'>
          <p>
            Regards,<br>
            <b>Hostel Office</b><br>
            PDPM IIITDM Jabalpur
          </p>
          <p style='font-size:12px;color:#888'>
            This is an automated message. Please do not reply.
          </p>
        </div>
      </div>
    </body>
    </html>
    ";

    if (sendMail($email, $subject, $message)) {
        $sentCount++;
    }
}

// ---------- ACTIVITY LOG ----------
$db->activity_logs->insertOne([
    'user_id'     => $admin['username'],
    'role'        => $admin['role'],
    'hostel'      => 'GLOBAL',
    'action_type' => 'CURFEW_MAIL_SENT',
    'description' => "Curfew mail sent to {$sentCount} students",
    'timestamp'   => $timestamp
]);

echo "Mail sent successfully to {$sentCount} students.";
