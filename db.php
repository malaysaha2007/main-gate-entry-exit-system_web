<?php
require __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->main_gate_entry_exit_system;
