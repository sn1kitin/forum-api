<?php
namespace App\Http;

class RequestHandler
{
    private array $routes;
    private string $method;
    private string $resource;
    private ?int $id = null;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->resource = strtolower($_GET['resource'] ?? '');

        if (isset($_GET['id'])) {
            // /threads/abc gaf eerst een 500 error
            if (!ctype_digit($_GET['id']) || (int) $_GET['id'] < 1) {
                ApiResponse::error(ApiResponse::BAD_REQUEST, 'Het id moet een positief geheel getal zijn.');
            }

            $this->id = (int) $_GET['id'];
        }
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

    private function allowedMethods(string $key): array
    {
        $allowed = [];

        foreach ($this->routes as $method => $routes) {
            if (isset($routes[$key])) {
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

        // threads of threads/{id}
        $key = $this->id === null ? $this->resource : $this->resource . '/{id}';

        $route = $this->routes[$this->method][$key] ?? null;

        if ($route === null) {
            $allowed = $this->allowedMethods($key);

            // url bestaat wel maar niet met deze methode
            if (!empty($allowed)) {
                header('Allow: ' . implode(', ', $allowed));
                ApiResponse::error(ApiResponse::METHOD_NOT_ALLOWED, "De methode {$this->method} is niet toegestaan voor deze url.");
            }

            ApiResponse::error(ApiResponse::NOT_FOUND, 'Deze route bestaat niet.');
        }

        [$class, $action] = $route;
        $controller = new $class();

        $args = [];

        if ($this->id !== null) {
            $args[] = $this->id;
        }

        if ($this->method === 'POST') {
            $args[] = $this->getBody();
        }

        $controller->$action(...$args);
    }
}
