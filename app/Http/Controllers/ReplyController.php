<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;
use App\Models\TopicModel;
use App\Models\UserModel;

class ReplyController extends Controller
{
    private const RULES = [
        'body' => 'required|string',
        'topic_id' => 'required|int|exists:' . TopicModel::class,
        'user_id' => 'required|int|exists:' . UserModel::class,
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
        $errors = $this->validate($data, self::RULES);

        if (!empty($errors)) {
            ApiResponse::error(ApiResponse::UNPROCESSABLE, 'De invoer is niet geldig.', $errors);
        }

        $reply = ReplyModel::create($data);

        header('Location: ' . ApiResponse::url('replies/' . $reply['id']));
        ApiResponse::send($reply, ApiResponse::CREATED);
    }
}
