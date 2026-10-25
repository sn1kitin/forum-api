<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;

class ReplyController
{
    // GET /replies
    public function index(): void
    {
        ApiResponse::send(ReplyModel::all());
    }

    // GET /replies/{id}
    public function show(int $id): void
    {
        ApiResponse::send(ReplyModel::find($id));
    }
}
