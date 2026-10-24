<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;

class ReplyController
{
    // GET /replies
    public function index(): void
    {
        $model = new ReplyModel();

        ApiResponse::send($model->all());
    }

    // GET /replies/{id}
    public function show(int $id): void
    {
        $model = new ReplyModel();

        ApiResponse::send($model->find($id));
    }
}
