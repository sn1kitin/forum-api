<?php
namespace App\Models;

class ThreadModel extends Model
{
    protected static string $table = 'threads';
    protected static array $fillable = ['user_id', 'title', 'description'];
}
