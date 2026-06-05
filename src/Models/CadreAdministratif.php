<?php
abstract class CadreAdministratif extends Utilisateur {

    // Méthode partagée par le Doyen et le Vice-Doyen pour envoyer une convocation de réunion
    public function convoquer($db, $objet, $date, $heure, $lieu, $message = '') {
        $texteMessage = "CONVOCATION : " . $objet . " | Date : " . $date . " à " . $heure . " | Lieu : " . $lieu;
        if (!empty($message)) {
            $texteMessage .= " | Notes : " . $message;
        }

        $queryMsg = "INSERT INTO messages (expediteur_id, contenu, type) VALUES (:expediteur_id, :contenu, 'convocation')";
        $stmtMsg = $db->prepare($queryMsg);
        $stmtMsg->execute([
            'expediteur_id' => $this->id,
            'contenu' => $texteMessage
        ]);

        $messageId = $db->lastInsertId();

        $queryConvoc = "INSERT INTO convocations (message_id, objet, date_heure, lieu) VALUES (:message_id, :objet, :date_heure, :lieu)";
        $stmtConvoc = $db->prepare($queryConvoc);
        
        $dateHeureComplete = $date . ' ' . $heure;

        return $stmtConvoc->execute([
            'message_id' => $messageId,
            'objet' => $objet,
            'date_heure' => $dateHeureComplete,
            'lieu' => $lieu
        ]);
    }
}
