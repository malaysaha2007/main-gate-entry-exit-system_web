<?php

header("Content-Type: application/json");

require '../db.php';

$logsCollection = $db->entry_exit_logs;

$cursor = $logsCollection->find(
    [],
    [
        'sort' => ['outTime' => -1]
    ]
);

$logs = [];

foreach ($cursor as $doc) {

    $logs[] = [
        "name"    => $doc['name'] ?? "",
        "roll"    => $doc['roll'] ?? "",
        "hostel"  => $doc['hostel'] ?? "",
        "room"    => $doc['room'] ?? "",
        "phone"   => $doc['phone'] ?? "",
        "purpose" => $doc['purpose'] ?? "",
        "outTime" => $doc['outTime'] ?? null,
        "inTime"  => $doc['inTime'] ?? null
    ];
}

echo json_encode($logs);

?>