<?php
require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode(['message' => 'Forum API werkt']);
