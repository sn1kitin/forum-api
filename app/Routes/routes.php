<?php

use App\Http\Controllers\ThreadController;

return [
    'GET' => [
        '' => [ThreadController::class, 'index'],
        'threads' => [ThreadController::class, 'index'],
        'threads/{id}' => [ThreadController::class, 'show'],
    ],
];
