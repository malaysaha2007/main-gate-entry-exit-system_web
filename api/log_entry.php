<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

require "../db.php";

/*  LOG ENTRY POINT */
error_log("log_entry.php HIT");

$raw = file_get_contents("php://input");
error_log("RAW INPUT: " . $raw);

$data = json_decode($raw, true);

if (!$data) {
    error_log("JSON DECODE FAILED");
    echo json_encode(["status"=>"error","message"=>"Invalid JSON"]);
    exit;
}

error_log("DECODED DATA: " . json_encode($data));

/*  FORCE INSERT — NO CONDITIONS */
$doc = [
    "name"       => $data["name"] ?? "UNKNOWN",
    "action"     => "ENTRY",   // force ENTRY for test
    "time"       => $data["time"] ?? date("Y-m-d H:i:s"),
    "confidence" => $data["confidence"] ?? null,
    "source"     => $data["source"] ?? "WEB_CAMERA",
    "created_at" => new MongoDB\BSON\UTCDateTime()
];

$result = $db->movements->insertOne($doc);

error_log("INSERTED ID: " . $result->getInsertedId());

echo json_encode([
    "status" => "success",
    "inserted_id" => (string)$result->getInsertedId(),
    "db" => "main_gate_entry_exit_system",
    "collection" => "movements"
]);
