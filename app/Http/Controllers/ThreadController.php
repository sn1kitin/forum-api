<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ThreadModel;

class ThreadController
{
    // GET /threads
    public function index(): void
    {
        $model = new ThreadModel();

        ApiResponse::send($model->all());
    }

    // GET /threads/{id}
    public function show(int $id): void
    {
        $model = new ThreadModel();

        ApiResponse::send($model->find($id));
    }
}
