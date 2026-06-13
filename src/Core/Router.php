<?php
namespace Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function add(string $method, string $path, $handler, array $middlewares = []): void
    {
        $path = $this->normalizePath($path);
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, $handler, array $middlewares = []): void
    {
        $this->add('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void
    {
        $this->add('POST', $path, $handler, $middlewares);
    }

    private function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                $this->runMiddlewares($route['middlewares']);
                $this->executeHandler($route['handler']);
                return;
            }
        }

        $this->response->setStatusCode(404);
        $this->response->json(['error' => 'Route not found'], 404);
    }

    private function matchPath(string $routePath, string $requestUri): bool
    {
        $routePattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        
        if (preg_match($routePattern, $requestUri, $matches)) {
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $this->request->setParam($key, $value);
                }
            }
            return true;
        }
        return false;
    }

    private function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $middlewareInstance = new $middleware();
            $middlewareInstance->handle($this->request, $this->response);
        }
    }

    private function executeHandler($handler): void
    {
        if (is_array($handler)) {
            $controller = new $handler[0]();
            $method = $handler[1];
            $controller->$method($this->request, $this->response);
        } elseif (is_callable($handler)) {
            $handler($this->request, $this->response);
        }
    }
}