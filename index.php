<?php
require __DIR__ . '/vendor/autoload.php';

use App\Database\Database;
use App\Http\ApiResponse;

try {
    $db = new Database();
    ApiResponse::send(['message' => 'Verbinding met database gelukt']);
} catch (Throwable $e) {
    error_log($e->getMessage());
    ApiResponse::error(ApiResponse::SERVER_ERROR, 'Er is iets misgegaan op de server.');
}
