<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use Services\ConvocationService;
use database\Database;

class ConvocationController extends Controller
{
    private ConvocationService $convocationService;

    public function __construct()
    {
        $database = new Database();
        $this->convocationService = new ConvocationService($database->getConnection());
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
        
        $success = $this->convocationService->sendConvocation($data);
        
        if ($success) {
            $response->json(['success' => true]);
        } else {
            $response->json(['success' => false, 'error' => 'Erreur lors de l\'envoi'], 500);
        }
    }
}