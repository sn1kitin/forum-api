<?php
require __DIR__ . '/vendor/autoload.php';

use App\Database\Database;

header('Content-Type: application/json; charset=utf-8');

$db = new Database();

echo json_encode(['message' => 'Verbinding met database gelukt']);
