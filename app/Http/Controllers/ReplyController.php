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
        $reply = ReplyModel::find($id);

        if ($reply === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Reply {$id} bestaat niet.");
        }

        ApiResponse::send($reply);
    }
}
