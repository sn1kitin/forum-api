<?php
namespace App\Models;

use App\Database\Database;

abstract class Model
{
    protected static string $table;
    // alleen deze velden mogen door de client worden ingevuld
    protected static array $fillable = [];

    private static ?Database $db = null;

    // een verbinding voor alle models
    protected static function db(): Database
    {
        if (self::$db === null) {
            self::$db = new Database();
        }

        return self::$db;
    }

    protected static function onlyFillable(array $data): array
    {
        return array_intersect_key($data, array_flip(static::$fillable));
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

    // alle rijen waar een kolom een bepaalde waarde heeft, bv alle topics van een thread
    public static function where(string $column, mixed $value): array
    {
        $table = static::$table;
        self::db()->query("SELECT * FROM `{$table}` WHERE `{$column}` = :value", ['value' => $value]);

        return self::db()->fetchAll();
    }

    public static function create(array $data): array
    {
        $table = static::$table;

        $data = static::onlyFillable($data);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $columns = array_keys($data);
        $columnList = '`' . implode('`, `', $columns) . '`';
        $placeholders = ':' . implode(', :', $columns);

        self::db()->query("INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})", $data);

        return static::find(self::db()->lastId());
    }

    // true als er echt iets verwijderd is
    public static function delete(int $id): bool
    {
        $table = static::$table;
        self::db()->query("DELETE FROM `{$table}` WHERE `id` = :id", ['id' => $id]);

        return self::db()->rowCount() > 0;
    }
}
