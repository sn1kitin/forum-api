<?php
namespace App\Http;

class ApiResponse
{
    public const API_VERSION = '1.0';
    public const API_NAME = 'forum_api';

    public const OK = 200;
    public const CREATED = 201;
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const UNPROCESSABLE = 422;
    public const SERVER_ERROR = 500;

    private const MESSAGES = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Content',
        500 => 'Internal Server Error',
    ];

    public static function send(mixed $data, int $status = self::OK): void
    {
        self::headers($status);

        $response = self::base($status, self::count($data));
        $response['data'] = $data;

        echo self::toJson($response);
        exit;
    }

    public static function error(int $status, string $message, array $details = []): void
    {
        self::headers($status);

        $error = ['message' => $message];

        if (!empty($details)) {
            $error['details'] = $details;
        }

        $response = self::base($status, 0);
        $response['error'] = $error;

        echo self::toJson($response);
        exit;
    }

    // antwoord op de preflight van de browser (OPTIONS)
    public static function preflight(): void
    {
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Accept');
        header('Access-Control-Max-Age: 86400');

        // ook hier de vaste json structuur
        self::send(null);
    }

    // volledige url voor de Location header, bv http://localhost/forum-api/threads/5
    public static function url(string $path): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        return "{$scheme}://{$_SERVER['HTTP_HOST']}{$base}/{$path}";
    }

    private static function headers(int $status): void
    {
        http_response_code($status);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: Location');
        header('Content-Type: application/json; charset=utf-8');
    }

    // deze velden moeten in elke response zitten
    private static function base(int $status, int $count): array
    {
        return [
            'api_version' => self::API_VERSION,
            'api_name' => self::API_NAME,
            'count' => $count,
            'status' => $status,
            'status_message' => self::MESSAGES[$status] ?? '',
        ];
    }

    // lijst = aantal items, een record = 1, niks = 0
    private static function count(mixed $data): int
    {
        if ($data === null) {
            return 0;
        }

        if (is_array($data) && array_is_list($data)) {
            return count($data);
        }

        return 1;
    }

    private static function toJson(array $response): string
    {
        return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
