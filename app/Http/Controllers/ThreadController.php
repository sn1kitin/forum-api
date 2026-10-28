<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;
use App\Models\ThreadModel;
use App\Models\TopicModel;

class ThreadController extends Controller
{
    private const RULES = [
        'title' => 'required|string|max:100',
        'description' => 'required|string',
        'user_id' => 'required|int',
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

    // GET /threads/{id}/topics/{id} -> een topic binnen een thread, met replies
    public function topic(int $threadId, int $topicId): void
    {
        $topic = TopicModel::find($topicId);

        if ($topic === null || (int) $topic['thread_id'] !== $threadId) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$topicId} bestaat niet in thread {$threadId}.");
        }

        $topic['replies'] = ReplyModel::where('topic_id', $topicId);

        ApiResponse::send($topic);
    }

    // POST /threads
    public function create(array $data): void
    {
        $this->validate($data, self::RULES);

        $thread = ThreadModel::create($data);

        header('Location: ' . ApiResponse::url('threads/' . $thread['id']));
        ApiResponse::send($thread, ApiResponse::CREATED);
    }

    // DELETE /threads/{id} -> geeft terug wat er verwijderd is
    public function destroy(int $id): void
    {
        $thread = ThreadModel::find($id);

        if ($thread === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Thread {$id} bestaat niet.");
        }

        ThreadModel::delete($id);

        ApiResponse::send($thread);
    }
}
