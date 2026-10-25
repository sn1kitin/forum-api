<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ThreadModel;

class ThreadController
{
    // GET /threads
    public function index(): void
    {
        ApiResponse::send(ThreadModel::all());
    }

    // GET /threads/{id}
    public function show(int $id): void
    {
        ApiResponse::send(ThreadModel::find($id));
    }
}
