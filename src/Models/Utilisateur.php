<?php
abstract class Utilisateur {
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $role;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->nom = $data['nom'] ?? '';
            $this->prenom = $data['prenom'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->role = $data['role'] ?? '';
        }
    }

    // Getters de base
    public function getId() { 
        return $this->id; 
    }
    
    public function getNom() { 
        return $this->nom; 
    }
    
    public function getPrenom() { 
        return $this->prenom; 
    }
    
    public function getEmail() { 
        return $this->email; 
    }
    
    public function getRole() { 
        return $this->role; 
    }

    public static function authentifier($db, $email, $password) {
        $query = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user; 
        }
        return false;
    }
}
