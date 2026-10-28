<?php
namespace App\Http;

class RequestHandler
{
    private array $routes;
    private string $method;
    // bv threads/{id}/topics
    private string $key = '';
    private array $ids = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->parseUrl();
    }

    // met resource en id via de htaccess kon ik geen /threads/1/topics doen, dus nu lees ik de url zelf uit
    private function parseUrl(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // projectmap eraf halen, bv /forum-api
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }

        $segments = array_values(array_filter(explode('/', strtolower($path)), fn($s) => $s !== ''));

        // threads/5/topics/3 -> even posities zijn resources, oneven posities zijn ids
        $parts = [];
        foreach ($segments as $i => $segment) {
            if ($i % 2 === 0) {
                $parts[] = $segment;
                continue;
            }

            if (!ctype_digit($segment) || (int) $segment < 1) {
                ApiResponse::error(ApiResponse::BAD_REQUEST, 'Het id moet een positief geheel getal zijn.');
            }

            $this->ids[] = (int) $segment;
            $parts[] = '{id}';
        }

        $this->key = implode('/', $parts);
    }

    // json body of gewone formdata
    private function getBody(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $body = file_get_contents('php://input');

            if (trim($body) === '') {
                return [];
            }

            $data = json_decode($body, true);

            if (!is_array($data)) {
                ApiResponse::error(ApiResponse::BAD_REQUEST, 'De body bevat geen geldige JSON.');
            }

            return $data;
        }

        return $_POST;
    }

    private function allowedMethods(): array
    {
        $allowed = [];

        foreach ($this->routes as $method => $routes) {
            if (isset($routes[$this->key])) {
                $allowed[] = $method;
            }
        }

        return $allowed;
    }

    public function handle(): void
    {
        // CORS preflight van de browser
        if ($this->method === 'OPTIONS') {
            ApiResponse::preflight();
        }

        $route = $this->routes[$this->method][$this->key] ?? null;

        if ($route === null) {
            $allowed = $this->allowedMethods();

            // url bestaat wel maar niet met deze methode
            if (!empty($allowed)) {
                header('Allow: ' . implode(', ', $allowed));
                ApiResponse::error(ApiResponse::METHOD_NOT_ALLOWED, "De methode {$this->method} is niet toegestaan voor deze url.");
            }

            ApiResponse::error(ApiResponse::NOT_FOUND, 'Deze route bestaat niet.');
        }

        [$class, $action] = $route;
        $controller = new $class();

        // eerst de ids uit de url, daarna de body
        $args = $this->ids;

        if ($this->method === 'POST') {
            $args[] = $this->getBody();
        }

        $controller->$action(...$args);
    }
}
