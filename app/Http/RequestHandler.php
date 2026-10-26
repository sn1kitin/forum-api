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

    public function handle(): void
    {
        // threads of threads/{id}
        $key = $this->id === null ? $this->resource : $this->resource . '/{id}';

        $route = $this->routes[$this->method][$key] ?? null;

        if ($route === null) {
            ApiResponse::error(ApiResponse::NOT_FOUND, 'Deze route bestaat niet.');
        }

        [$class, $action] = $route;
        $controller = new $class();

        if ($this->id === null) {
            $controller->$action();
        } else {
            $controller->$action($this->id);
        }
    }
}
