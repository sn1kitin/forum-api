<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ThreadModel;
use App\Models\TopicModel;
use App\Models\UserModel;

class ThreadController extends Controller
{
    private const RULES = [
        'title' => 'required|string|max:100',
        'description' => 'required|string',
        'user_id' => 'required|int|exists:' . UserModel::class,
    ];

    // GET /threads
    public function index(): void
    {
        ApiResponse::send(ThreadModel::all());
    }

    // GET /threads/{id}
    public function show(int $id): void
    {
        $thread = ThreadModel::find($id);

        if ($thread === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Thread {$id} bestaat niet.");
        }

        ApiResponse::send($thread);
    }

    // GET /threads/{id}/topics -> thread met al zijn topics
    public function topics(int $id): void
    {
        $thread = ThreadModel::find($id);

        if ($thread === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Thread {$id} bestaat niet.");
        }

        $thread['topics'] = TopicModel::where('thread_id', $id);

        ApiResponse::send($thread);
    }

    // POST /threads
    public function create(array $data): void
    {
        $this->validate($data, self::RULES);

        $thread = ThreadModel::create($data);

        header('Location: ' . ApiResponse::url('threads/' . $thread['id']));
        ApiResponse::send($thread, ApiResponse::CREATED);
    }
}
