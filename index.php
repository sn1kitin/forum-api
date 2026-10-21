<?php
require __DIR__ . '/vendor/autoload.php';

use App\Http\ApiResponse;
use App\Http\RequestHandler;

try {
    $routes = require __DIR__ . '/app/Routes/routes.php';

    $handler = new RequestHandler($routes);
    $handler->handle();
} catch (Throwable $e) {
    // echte fout alleen in de log, niet naar de client
    error_log($e->getMessage());
    ApiResponse::error(ApiResponse::SERVER_ERROR, 'Er is iets misgegaan op de server.');
}
