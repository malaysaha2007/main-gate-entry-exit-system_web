<?php
header("Content-Type: application/json");

// MongoDB connection
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Query entry_exit_logs collection
$query = new MongoDB\Driver\Query(
    [],
    ['sort' => ['outTime' => -1]]
);

$cursor = $mongo->executeQuery(
    "main_gate_entry_exit_system.entry_exit_logs",
    $query
);

$logs = [];

foreach ($cursor as $doc) {
    $logs[] = [
        "name"    => $doc->name ?? "",
        "roll"    => $doc->roll ?? "",
        "hostel"  => $doc->hostel ?? "",
        "room"    => $doc->room ?? "",
        "phone"   => $doc->phone ?? "",
        "purpose" => $doc->purpose ?? "",
        "outTime" => $doc->outTime ?? null,
        "inTime"  => $doc->inTime ?? null
    ];
}

echo json_encode($logs);
