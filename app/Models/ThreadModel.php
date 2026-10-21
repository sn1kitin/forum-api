<?php
namespace App\Models;

use App\Database\Database;

class ThreadModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all(): array
    {
        $this->db->query('SELECT * FROM `threads`');

        return $this->db->fetchAll();
    }

    public function find(int $id): ?array
    {
        $this->db->query('SELECT * FROM `threads` WHERE `id` = :id', ['id' => $id]);

        return $this->db->fetch();
    }
}
