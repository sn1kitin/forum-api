<?php
namespace App\Http;

class RequestHandler
{
    private array $routes;
    private string $method;
    private string $resource = '/';
    private ?int $id = null;
    // bv topics bij /threads/1/topics
    private ?string $relation = null;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->parseURI();
    }

    // Haalt de resource, het id en de relation uit de URL (die .htaccess in $_GET heeft gezet)
    private function parseURI(): void
    {
        if (isset($_GET['resource'])) {
            $this->resource = strtolower($_GET['resource']);
        } else {
            $this->resource = '/';
        }

        if (isset($_GET['id'])) {
            if (!ctype_digit($_GET['id']) || (int) $_GET['id'] < 1) {
                ApiResponse::error(ApiResponse::BAD_REQUEST, 'Het id moet een positief geheel getal zijn.');
            }

            $this->id = (int) $_GET['id'];
        }

        if (isset($_GET['relation'])) {
            $this->relation = strtolower($_GET['relation']);
        }
    }

    // Maakt de sleutel om in routes.php te zoeken: 'threads', 'threads/{id}' of 'threads/{id}/topics'
    private function getRouteKey(): string
    {
        if ($this->id === null) {
            return $this->resource;
        }

        if ($this->relation !== null) {
            return $this->resource . '/{id}/' . $this->relation;
        }

        return $this->resource . '/{id}';
    }

    // Leest de data die de client in de body heeft gestuurd (json of gewone formdata)
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

    // Geeft de methodes die wel bestaan voor deze url (nodig voor de 405)
    private function allowedMethods(): array
    {
        $allowed = [];

        foreach ($this->routes as $method => $routes) {
            if (isset($routes[$this->getRouteKey()])) {
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

        $route = $this->routes[$this->method][$this->getRouteKey()] ?? null;

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

        // eerst het id uit de url, daarna de body
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
