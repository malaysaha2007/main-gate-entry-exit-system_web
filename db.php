<?php
require __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb+srv://malay07_db_user:Malay07%40@prproject.h4mjvbl.mongodb.net/?retryWrites=true&w=majority");

$db = $client->main_gate_entry_exit_system;
?>