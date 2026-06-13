<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Services\ConvocationService;
use database\Database;

class ConvocationController extends Controller
{
    private ConvocationService $service;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->service = new ConvocationService($database->getConnection());
    }

    public function send(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $required = ['objet', 'date', 'heure', 'lieu'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $response->json(['success' => false, 'error' => "Champ $field requis"], 400);
                return;
            }
        }
        
        $success = $this->service->sendConvocation($data);
        $response->json(['success' => $success]);
    }
}