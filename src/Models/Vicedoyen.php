<?php
namespace Models;

class Vicedoyen extends Utilisateur
{
    public function convoquer($db, $objet, $date, $heure, $lieu, $message)
    {
        $sql = "SELECT id FROM utilisateurs WHERE role IN ('enseignant', 'assistant')";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll();
        $userIds = array_column($users, 'id');
        
        $convocation = new Convocation($db);
        return $convocation->create($objet, $date, $heure, $lieu, $message, $userIds, SessionHelper::getUserId());
    }
}