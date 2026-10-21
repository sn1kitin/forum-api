<?php
namespace App\Http;

class RequestHandler
{
    private array $routes;
    private string $method;
    private string $resource;
    private ?string $id;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->resource = strtolower($_GET['resource'] ?? '');
        $this->id = $_GET['id'] ?? null;
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
