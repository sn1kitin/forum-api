<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ReplyController;

return [
    'GET' => [
        '' => [ThreadController::class, 'index'],
        'threads' => [ThreadController::class, 'index'],
        'threads/{id}' => [ThreadController::class, 'show'],
        'topics' => [TopicController::class, 'index'],
        'topics/{id}' => [TopicController::class, 'show'],
        'replies' => [ReplyController::class, 'index'],
        'replies/{id}' => [ReplyController::class, 'show'],
    ],
    'POST' => [
        'threads' => [ThreadController::class, 'create'],
    ],
];
