<?php
namespace App\Models;

class ReplyModel extends Model
{
    protected static string $table = 'replies';
    protected static array $fillable = ['topic_id', 'user_id', 'body'];
}
