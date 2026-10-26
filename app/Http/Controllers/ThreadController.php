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
        $thread = ThreadModel::find($id);

        if ($thread === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, "Thread {$id} bestaat niet.");
        }

        ApiResponse::send($thread);
    }

    // POST /threads
    public function create(array $data): void
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Het veld title is verplicht.';
        } elseif (mb_strlen($data['title']) > 100) {
            $errors['title'] = 'Het veld title mag maximaal 100 tekens zijn.';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Het veld description is verplicht.';
        }

        if (empty($data['user_id']) || !ctype_digit((string) $data['user_id'])) {
            $errors['user_id'] = 'Het veld user_id moet een getal zijn.';
        }

        if (!empty($errors)) {
            ApiResponse::error(ApiResponse::UNPROCESSABLE, 'De invoer is niet geldig.', $errors);
        }

        $thread = ThreadModel::create($data);

        header('Location: ' . ApiResponse::url('threads/' . $thread['id']));
        ApiResponse::send($thread, ApiResponse::CREATED);
    }
}
