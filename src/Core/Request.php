<?php
namespace Core;

class Request
{
    private array $params = [];

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '/FasiChatClassroom/public';
        return str_replace($basePath, '', $uri);
    }

    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function getFiles(): array
    {
        return $_FILES;
    }

    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function setParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function getQueryParams(): array
    {
        return $_GET;
    }
}