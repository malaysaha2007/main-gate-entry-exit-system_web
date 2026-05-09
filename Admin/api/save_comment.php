<?php
session_start();
require '../../db.php';

header('Content-Type: application/json');

// ---------- AUTH ----------
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ---------- COLLECTIONS ----------
$entryLogs   = $db->entry_exit_logs;
$activityLog = $db->activity_logs;

// ---------- INPUT ----------
$roll    = trim($_POST['roll'] ?? '');
$comment = trim($_POST['comment'] ?? '');

if ($roll === '' || $comment === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Roll number and comment are required']);
    exit;
}

// ---------- TIME ----------
date_default_timezone_set('Asia/Kolkata');
$timestamp    = date('Y-m-d H:i:s');      // for MongoDB
$fileDateTime = date('d.m.Y_H.i.s');      // for filename

// ---------- ADMIN ----------
$adminUser = $_SESSION['admin']['username'];
$adminRole = $_SESSION['admin']['role'];
$adminKey  = strtolower(str_replace(' ', '', $adminRole));

// ---------- FETCH STUDENT ----------
$student = $entryLogs->findOne(
    ['roll' => $roll],
    ['projection' => ['name' => 1]]
);

if (!$student) {
    http_response_code(404);
    echo json_encode(['error' => 'Student not found']);
    exit;
}

$studentName = $student['name'] ?? 'Unknown';

// ---------- FILE UPLOAD ----------
$fileData = null;

if (!empty($_FILES['document']['name'])) {

    $allowed = ['jpg','jpeg','png','pdf','doc','docx'];
    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }

    // ✅ REQUIRED FILENAME FORMAT
    // ROLLNO_role_DD.MM.YYYY_HH.MM.SS.ext
    $fileName = $roll . '_' . $adminKey . '_' . $fileDateTime . '.' . $ext;

    $uploadDir = '../Comments_documents/';
    $target    = $uploadDir . $fileName;

    if (!move_uploaded_file($_FILES['document']['tmp_name'], $target)) {
        http_response_code(500);
        echo json_encode(['error' => 'File upload failed']);
        exit;
    }

    $fileData = [
        'name' => $fileName,
        'path' => 'Admin/Comments_documents/' . $fileName,
        'type' => $_FILES['document']['type']
    ];
}

// ---------- UPDATE COMMENT IN entry_exit_logs ----------
$entryLogs->updateOne(
    ['roll' => $roll],
    [
        '$set' => [
            // ✅ EASY ACCESS FIELD
            'comment_text' => $comment,

            // ✅ STRUCTURED COMMENT OBJECT
            'comment' => [
                'file' => $fileData,
                'commented_by' => [
                    'username' => $adminUser,
                    'role'     => $adminRole
                ],
                'commented_at' => $timestamp
            ]
        ]
    ]
);


// ---------- ACTIVITY LOG (ALLOW MULTIPLE PER DAY) ----------
$activityLog->insertOne([
    'user_id'     => $adminUser,
    'role'        => $adminRole,
    'hostel'      => 'GLOBAL',
    'action_type' => 'COMMENT_ADDED',
    'description' => "Commented student {$roll} ({$studentName})",
    'comment_text'=> $comment,
    'timestamp'   => $timestamp
]);

echo json_encode(['success' => true]);
