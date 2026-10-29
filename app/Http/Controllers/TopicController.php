<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\ReplyModel;
use App\Models\ThreadModel;
use App\Models\TopicModel;

class TopicController extends Controller
{
    private const RULES = [
        'title' => 'required|string|max:100',
        'body' => 'required|string',
        'thread_id' => 'required|int|exists:' . ThreadModel::class,
        'user_id' => 'required|int',
    ];

    // GET /topics
    public function index(): void
    {
        ApiResponse::send(TopicModel::all());
    }

    // GET /topics/{id}
    public function show(int $id): void
    {
        $topic = TopicModel::find($id);

        if ($topic === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$id} bestaat niet.");
        }

        ApiResponse::send($topic);
    }

    // GET /topics/{id}/replies -> topic met al zijn replies
    public function replies(int $id): void
    {
        $topic = TopicModel::find($id);

        if ($topic === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$id} bestaat niet.");
        }

        $topic['replies'] = ReplyModel::where('topic_id', $id);

        ApiResponse::send($topic);
    }

    // POST /topics
    public function create(array $data): void
    {
        $this->validate($data, self::RULES);

        $topic = TopicModel::create($data);

        header('Location: ' . ApiResponse::url('topics/' . $topic['id']));
        ApiResponse::send($topic, ApiResponse::CREATED);
    }

    // PUT /topics/{id}
    public function update(int $id, array $data): void
    {
        $this->save($id, $data, false);
    }

    // PATCH /topics/{id}
    public function patch(int $id, array $data): void
    {
        $this->save($id, $data, true);
    }

    private function save(int $id, array $data, bool $partial): void
    {
        if (TopicModel::find($id) === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$id} bestaat niet.");
        }

        $this->validate($data, self::RULES, $partial);

        ApiResponse::send(TopicModel::update($id, $data));
    }

    // DELETE /topics/{id} -> geeft terug wat er verwijderd is
    public function destroy(int $id): void
    {
        $topic = TopicModel::find($id);

        if ($topic === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Topic {$id} bestaat niet.");
        }

        TopicModel::delete($id);

        ApiResponse::send($topic);
    }
}
