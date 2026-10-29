<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ReplyController;

return [
    'GET' => [
        '' => [ThreadController::class, 'index'],
        'threads' => [ThreadController::class, 'index'],
        'threads/{id}' => [ThreadController::class, 'show'],
        'threads/{id}/topics' => [ThreadController::class, 'topics'],
        'threads/{id}/topics/{id}' => [ThreadController::class, 'topic'],
        'topics' => [TopicController::class, 'index'],
        'topics/{id}' => [TopicController::class, 'show'],
        'topics/{id}/replies' => [TopicController::class, 'replies'],
        'replies' => [ReplyController::class, 'index'],
        'replies/{id}' => [ReplyController::class, 'show'],
    ],
    'POST' => [
        'threads' => [ThreadController::class, 'create'],
        'topics' => [TopicController::class, 'create'],
        'replies' => [ReplyController::class, 'create'],
    ],
    // PUT = alle velden, PATCH = alleen wat je meestuurt
    'PUT' => [
        'threads/{id}' => [ThreadController::class, 'update'],
        'topics/{id}' => [TopicController::class, 'update'],
        'replies/{id}' => [ReplyController::class, 'update'],
    ],
    'PATCH' => [
        'threads/{id}' => [ThreadController::class, 'patch'],
        'topics/{id}' => [TopicController::class, 'patch'],
        'replies/{id}' => [ReplyController::class, 'patch'],
    ],
    'DELETE' => [
        'threads/{id}' => [ThreadController::class, 'destroy'],
        'topics/{id}' => [TopicController::class, 'destroy'],
        'replies/{id}' => [ReplyController::class, 'destroy'],
    ],
];
