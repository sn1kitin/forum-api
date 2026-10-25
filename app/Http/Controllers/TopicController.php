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
        ApiResponse::send(TopicModel::find($id));
    }
}
