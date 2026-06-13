<?php
namespace Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require VIEWS_PATH . $view . '.php';
    }

    protected function json($data, int $statusCode = 200): void
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
        $response->json($data);
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }
}