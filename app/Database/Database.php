<?php
namespace App\Database;

use PDO;
use PDOStatement;

class Database
{
    // XAMPP standaard: root zonder wachtwoord
    private string $host = '127.0.0.1';
    private string $dbName = 'forum_api_db';
    private string $user = 'root';
    private string $pass = '';

    private PDO $pdo;
    private ?PDOStatement $stmt = null;

    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function query(string $sql, array $params = []): void
    {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($params);
    }

    public function fetch(): ?array
    {
        $row = $this->stmt->fetch();

        return $row === false ? null : $row;
    }

    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }
}
