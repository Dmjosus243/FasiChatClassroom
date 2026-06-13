<?php
namespace Core;

class Response
{
    private int $statusCode = 200;

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
        http_response_code($code);
    }

    public function json(array $data, int $statusCode = 200): void
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function send(string $content, int $statusCode = 200): void
    {
        $this->setStatusCode($statusCode);
        echo $content;
        exit();
    }
}