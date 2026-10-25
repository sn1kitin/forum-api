<?php
namespace App\Models;

use App\Database\Database;

abstract class Model
{
    protected static string $table;

    private static ?Database $db = null;

    // een verbinding voor alle models
    protected static function db(): Database
    {
        if (self::$db === null) {
            self::$db = new Database();
        }

        return self::$db;
    }

    public static function all(): array
    {
        $table = static::$table;
        self::db()->query("SELECT * FROM `{$table}`");

        return self::db()->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $table = static::$table;
        self::db()->query("SELECT * FROM `{$table}` WHERE `id` = :id", ['id' => $id]);

        return self::db()->fetch();
    }
}
