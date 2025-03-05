<?php
// app/Models/Offer.php

class Offer {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Récupère toutes les offres avec le nom de l'entreprise.
     */
    public function getAllWithCompanyName() {
        $sql = "SELECT o.offer_id, o.title, o.company_id, o.description, o.competencies, 
                       o.remuneration_base, o.start_date, o.end_date, c.name AS company_name 
                FROM offers o 
                INNER JOIN companies c ON o.company_id = c.company_id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère toutes les offres triées par titre.
     */
    public function getAllSortedByTitle() {
        $sql = "SELECT o.offer_id, o.title, o.company_id, o.description, o.competencies, 
                       o.remuneration_base, o.start_date, o.end_date, c.name AS company_name 
                FROM offers o 
                INNER JOIN companies c ON o.company_id = c.company_id 
                ORDER BY o.title ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Recherche les offres en classant en premier celles dont le titre ou la description
     * correspondent au terme recherché.
     *
     * @param string $term Le terme de recherche.
     * @return array Les offres triées par pertinence, puis par titre.
     */
    public function searchOrderByMatch($term) {
        $sql = "SELECT o.offer_id, o.title, o.company_id, o.description, o.competencies, 
                       o.remuneration_base, o.start_date, o.end_date, c.name AS company_name 
                FROM offers o 
                INNER JOIN companies c ON o.company_id = c.company_id 
                WHERE o.title LIKE ? OR o.description LIKE ? 
                ORDER BY (CASE WHEN o.title LIKE ? OR o.description LIKE ? THEN 0 ELSE 1 END), o.title ASC";
        $stmt = $this->db->prepare($sql);
        $likeTerm = '%' . $term . '%';
        $stmt->execute([$likeTerm, $likeTerm, $likeTerm, $likeTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère toutes les offres (avec toutes les colonnes nécessaires).
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT offer_id, title, company_id, description, competencies, remuneration_base, start_date, end_date FROM offers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crée une nouvelle offre.
     */
    public function create($company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date) {
        $stmt = $this->db->prepare("INSERT INTO offers (company_id, title, description, competencies, remuneration_base, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date]);
    }
    
    /**
     * Récupère une offre par son identifiant.
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM offers WHERE offer_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Met à jour une offre existante.
     */
    public function update($id, $company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date) {
        $stmt = $this->db->prepare("UPDATE offers SET company_id = ?, title = ?, description = ?, competencies = ?, remuneration_base = ?, start_date = ?, end_date = ? WHERE offer_id = ?");
        return $stmt->execute([$company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date, $id]);
    }
    
    /**
     * Supprime une offre.
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM offers WHERE offer_id = ?");
        return $stmt->execute([$id]);
    }
    public function searchByCriteria($company, $title, $competencies) {
        $sql = "SELECT o.offer_id, o.title, o.company_id, o.description, o.competencies, 
                       o.remuneration_base, o.start_date, o.end_date, c.name AS company_name 
                FROM offers o 
                INNER JOIN companies c ON o.company_id = c.company_id 
                WHERE 1=1";
        $params = [];
        
        if ($company != '') {
            $sql .= " AND c.name LIKE ?";
            $params[] = '%' . $company . '%';
        }
        if ($title != '') {
            $sql .= " AND o.title LIKE ?";
            $params[] = '%' . $title . '%';
        }
        if ($competencies != '') {
            $sql .= " AND o.competencies LIKE ?";
            $params[] = '%' . $competencies . '%';
        }
        
        $sql .= " ORDER BY c.name ASC, o.title ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
