<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;
use App\Models\TopicModel;

class ReplyController extends Controller
{
    private const RULES = [
        'body' => 'required|string',
        'topic_id' => 'required|int|exists:' . TopicModel::class,
        'user_id' => 'required|int',
    ];

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

    // POST /replies
    public function create(array $data): void
    {
        $this->validate($data, self::RULES);

        $reply = ReplyModel::create($data);

        header('Location: ' . ApiResponse::url('replies/' . $reply['id']));
        ApiResponse::send($reply, ApiResponse::CREATED);
    }

    // DELETE /replies/{id} -> geeft terug wat er verwijderd is
    public function destroy(int $id): void
    {
        $reply = ReplyModel::find($id);

        if ($reply === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Reply {$id} bestaat niet.");
        }

        ReplyModel::delete($id);

        ApiResponse::send($reply);
    }
}
