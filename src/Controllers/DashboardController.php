<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;

class DashboardController extends Controller
{
    public function etudiant(Request $request, Response $response): void
    {
        $this->render('dashboard_etudiant', [
            'user' => SessionHelper::getUser()
        ]);
    }

    public function enseignant(Request $request, Response $response): void
    {
        $this->render('dashboard_enseignant', [
            'user' => SessionHelper::getUser()
        ]);
    }

    public function assistant(Request $request, Response $response): void
    {
        $this->render('dashboard_assistant', [
            'user' => SessionHelper::getUser()
        ]);
    }

    public function apparitaire(Request $request, Response $response): void
    {
        $this->render('dashboard_apparitaire', [
            'user' => SessionHelper::getUser()
        ]);
    }

    public function doyen(Request $request, Response $response): void
    {
        $this->render('dashboard_doyen', [
            'user' => SessionHelper::getUser()
        ]);
    }

    public function vicedoyen(Request $request, Response $response): void
    {
        $this->render('dashboard_vicedoyen', [
            'user' => SessionHelper::getUser()
        ]);
    }
}