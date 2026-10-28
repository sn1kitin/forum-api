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
    'DELETE' => [
        'threads/{id}' => [ThreadController::class, 'destroy'],
        'topics/{id}' => [TopicController::class, 'destroy'],
        'replies/{id}' => [ReplyController::class, 'destroy'],
    ],
];
