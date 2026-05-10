<?php
session_start();

require '../db.php';
require '../config/mail.php';
require '../config/curfew.php';



/* 🔁 CHANGED COLLECTION */
$studentsCollection = $db->student_auth_data;     // ✅ NEW
$logsCollection     = $db->entry_exit_logs;

/* ===============================
   Form Inputs
================================ */
$studentid = trim($_POST['studentid'] ?? ''); // this is roll_no now
$password  = $_POST['password'] ?? '';

$login_failed = false;

/* ===============================
   Login Logic
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($studentid === '' || $password === '') {
        $login_failed = true;
    } else {

        /* 🔁 CHANGED QUERY FIELDS */
        $student = $studentsCollection->findOne([
            'roll_no'  => $studentid,   // ✅ NEW
            'password' => $password     // plain text (as you want)
        ]);

        if ($student) {

            /* ===============================
               TIME & LATE ENTRY CHECK
            ================================ */
            date_default_timezone_set('Asia/Kolkata');

            $entryDateTime = new DateTime('now');
            $curfewDateTime = new DateTime(date('Y-m-d') . ' ' . CURFEW_TIME);
            $isLate = ($entryDateTime > $curfewDateTime);

            $entryDateUTC = new MongoDB\BSON\UTCDateTime(
                $entryDateTime->getTimestamp() * 1000
            );

            /* ===============================
               UPDATE STUDENT STATUS
            ================================ */
            $studentsCollection->updateOne(
                ['roll_no' => $studentid],   // ✅ NEW
                [
                    '$set' => [
                        'lastEntryTime' => $entryDateUTC,
                        'lateEntry'     => $isLate
                    ]
                ]
            );

            // /* ===============================
            //    INSERT ENTRY LOG
            // ================================ */
            // $logsCollection->insertOne([
            //     'roll_no'   => $studentid,    // ✅ NEW
            //     'type'      => 'ENTRY',
            //     'datetime'  => $entryDateUTC,
            //     'purpose'   => $isLate ? 'Late Entry' : 'Normal Entry'
            // ]);

            // /* ===============================
            //    SEND EMAIL IF LATE
            // ================================ */
            // if ($isLate && !empty($student['email'])) {

            //     $subject = 'Late Entry Notice | Hostel Office';

            //     $body = '
            //     <html>
            //     <body>
            //       <h3>Late Entry Notice</h3>
            //       <p>Dear ' . htmlspecialchars($student["name"]) . ',</p>
            //       <p>You entered the hostel after curfew time.</p>
            //       <p><b>Entry Time:</b> ' . $entryDateTime->format('d M Y, h:i A') . '</p>
            //       <p>Regards,<br>Hostel Office</p>
            //     </body>
            //     </html>';

            //     sendMail($student['email'], $subject, $body);
            // }

            /* ===============================
               FETCH LOGS
            ================================ */
            $logsCursor = $logsCollection->find(
                ['roll_no' => $studentid],    // ✅ NEW
                ['sort' => ['datetime' => -1]]
            );

            $logs = [];
            foreach ($logsCursor as $log) {
                $logs[] = [
                    'type'     => $log['type'],
                    'datetime' => $log['datetime']->toDateTime()->format('d/m/Y H:i'),
                    'purpose'  => $log['purpose']
                ];
            }

            /* ===============================
               STORE SESSION (UNCHANGED)
            ================================ */
            $_SESSION['student'] = [
                'studentid' => $student['roll_no'],   // mapped
                'name'      => $student['name'],
                'roll_no'   => $student['roll_no'],
                'hostel'    => $student['hostel'],
                'room'      => $student['room'],
                'contact'   => $student['contact'],
                'log'       => $logs
            ];

            header("Location: student_profile.php");
            exit;

        } else {
            $login_failed = true;
        }
    }
}
?>

<?php if ($login_failed): ?>
<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;margin-top:80px;">
  <h2 style="color:red;">❌ Login Failed</h2>
  <p>Invalid Roll Number or Password</p>
  <a href="student_login.php">Try Again</a>
</div>
</body>
</html>
<?php endif; ?>
