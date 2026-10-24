<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\TopicModel;

class TopicController
{
    // GET /topics
    public function index(): void
    {
        $model = new TopicModel();

        ApiResponse::send($model->all());
    }

    // GET /topics/{id}
    public function show(int $id): void
    {
        $model = new TopicModel();

        ApiResponse::send($model->find($id));
    }
}
