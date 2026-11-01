<?php
namespace App\Models;

class UserModel extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = ['name', 'username', 'email', 'password'];
}
