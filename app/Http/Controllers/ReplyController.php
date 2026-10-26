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

    // POST /replies
    public function create(array $data): void
    {
        $errors = [];

        if (empty($data['body'])) {
            $errors['body'] = 'Het veld body is verplicht.';
        }

        if (empty($data['topic_id']) || !ctype_digit((string) $data['topic_id'])) {
            $errors['topic_id'] = 'Het veld topic_id moet een getal zijn.';
        }

        if (empty($data['user_id']) || !ctype_digit((string) $data['user_id'])) {
            $errors['user_id'] = 'Het veld user_id moet een getal zijn.';
        }

        if (!empty($errors)) {
            ApiResponse::error(ApiResponse::UNPROCESSABLE, 'De invoer is niet geldig.', $errors);
        }

        $reply = ReplyModel::create($data);

        header('Location: ' . ApiResponse::url('replies/' . $reply['id']));
        ApiResponse::send($reply, ApiResponse::CREATED);
    }
}
