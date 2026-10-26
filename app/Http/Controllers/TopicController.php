<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\TopicModel;

class TopicController
{
    // GET /topics
    public function index(): void
    {
        ApiResponse::send(TopicModel::all());
    }

    // GET /topics/{id}
    public function show(int $id): void
    {
        $topic = TopicModel::find($id);

        if ($topic === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$id} bestaat niet.");
        }

        ApiResponse::send($topic);
    }
}
