<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\TopicModel;

class TopicController
{
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

    // POST /topics
    public function create(array $data): void
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Het veld title is verplicht.';
        } elseif (mb_strlen($data['title']) > 100) {
            $errors['title'] = 'Het veld title mag maximaal 100 tekens zijn.';
        }

        if (empty($data['body'])) {
            $errors['body'] = 'Het veld body is verplicht.';
        }

        if (empty($data['thread_id']) || !ctype_digit((string) $data['thread_id'])) {
            $errors['thread_id'] = 'Het veld thread_id moet een getal zijn.';
        }

        if (empty($data['user_id']) || !ctype_digit((string) $data['user_id'])) {
            $errors['user_id'] = 'Het veld user_id moet een getal zijn.';
        }

        if (!empty($errors)) {
            ApiResponse::error(ApiResponse::UNPROCESSABLE, 'De invoer is niet geldig.', $errors);
        }

        $topic = TopicModel::create($data);

        header('Location: ' . ApiResponse::url('topics/' . $topic['id']));
        ApiResponse::send($topic, ApiResponse::CREATED);
    }
}
