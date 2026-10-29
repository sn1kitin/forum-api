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

    // PUT /replies/{id}
    public function update(int $id, array $data): void
    {
        $this->save($id, $data, false);
    }

    // PATCH /replies/{id}
    public function patch(int $id, array $data): void
    {
        $this->save($id, $data, true);
    }

    private function save(int $id, array $data, bool $partial): void
    {
        if (ReplyModel::find($id) === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Reply {$id} bestaat niet.");
        }

        $this->validate($data, self::RULES, $partial);

        ApiResponse::send(ReplyModel::update($id, $data));
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
