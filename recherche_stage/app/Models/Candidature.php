<?php
// app/Models/Candidature.php

class Candidature {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
// Dans app/Models/Candidature.php
public function create($user_id, $offer_id, $date_candidature, $cv_path, $motivationLetterPath, $candidateName) {
    $sql = "INSERT INTO candidatures (user_id, offer_id, date_candidature, cv_path, motivation_letter, candidate_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$user_id, $offer_id, $date_candidature, $cv_path, $motivationLetterPath, $candidateName]);
}


    
    
public function getByUserId($user_id) {
    $sql = "SELECT c.candidature_id,
                   c.user_id,
                   c.offer_id,
                   c.date_candidature,
                   c.cv_path,
                   c.motivation_letter,
                   u.first_name,
                   u.last_name,
                   o.title AS offer_title,
                   comp.name AS company_name
            FROM candidatures c
            INNER JOIN users u       ON c.user_id = u.user_id
            INNER JOIN offers o      ON c.offer_id = o.offer_id
            INNER JOIN companies comp ON o.company_id = comp.company_id
            WHERE c.user_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    
    /**
     * Récupère toutes les candidatures en joignant `users`, `offers` et `companies`.
     * Ainsi, on peut afficher :
     * - nom du candidat (first_name + last_name),
     * - titre de l'offre (offer_title),
     * - nom de l'entreprise (company_name).
     */
    public function getAllWithCandidateOfferAndCompany() {
        $sql = "SELECT c.candidature_id,
                       c.user_id,
                       c.offer_id,
                       c.date_candidature,
                       c.cv_path,
                       c.motivation_letter,
                       CONCAT(u.first_name, ' ', u.last_name) AS candidate_name,
                       o.title AS offer_title,
                       comp.name AS company_name
                FROM candidatures c
                INNER JOIN users u       ON c.user_id = u.user_id
                INNER JOIN offers o      ON c.offer_id = o.offer_id
                INNER JOIN companies comp ON o.company_id = comp.company_id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM candidatures WHERE candidature_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($candidature_id, $motivation_letter) {
        $stmt = $this->db->prepare("UPDATE candidatures SET motivation_letter = ? WHERE candidature_id = ?");
        return $stmt->execute([$motivation_letter, $candidature_id]);
    }
    
    public function delete($candidature_id) {
        $stmt = $this->db->prepare("DELETE FROM candidatures WHERE candidature_id = ?");
        return $stmt->execute([$candidature_id]);
    }
}
