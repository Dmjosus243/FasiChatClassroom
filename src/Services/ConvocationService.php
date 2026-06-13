<?php
namespace Services;

use Models\Doyen;
use Models\Vicedoyen;
use Helpers\SessionHelper;

class ConvocationService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function sendConvocation(array $data): bool
    {
        $user = SessionHelper::getUser();
        $role = $user['role'];
        
        if (!in_array($role, ['doyen', 'vice-doyen'])) return false;
        
        $admin = ($role === 'doyen') 
            ? new Doyen($this->db, $user) 
            : new Vicedoyen($this->db, $user);
        
        return $admin->convoquer(
            $this->db,
            $data['objet'],
            $data['date'],
            $data['heure'],
            $data['lieu'],
            $data['message'] ?? ''
        );
    }
}