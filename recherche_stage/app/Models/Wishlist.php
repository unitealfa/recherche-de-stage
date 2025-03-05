<?php
// app/Models/Wishlist.php

class Wishlist {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Ajouter une offre à la wish list
    public function add($user_id, $offer_id) {
        $stmt = $this->db->prepare("INSERT INTO wishlist (user_id, offer_id, date_added) VALUES (?, ?, NOW())");
        return $stmt->execute([$user_id, $offer_id]);
    }
    
    // Vérifier si l'élément existe déjà dans la wish list
    public function exists($user_id, $offer_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ? AND offer_id = ?");
        $stmt->execute([$user_id, $offer_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
    
    // Récupérer les détails des offres de la wish list d'un utilisateur
    // Cette méthode joint la table wishlist avec offers et companies pour récupérer :
    // - l'ID de la wishlist, la date d'ajout,
    // - l'ID de l'offre, le titre de l'offre,
    // - le nom de l'entreprise.
    public function getDetailsByUserId($user_id) {
        $sql = "SELECT w.wishlist_id, w.date_added, 
                       o.offer_id, o.title, o.company_id, 
                       c.name AS company_name
                FROM wishlist w
                INNER JOIN offers o ON w.offer_id = o.offer_id
                INNER JOIN companies c ON o.company_id = c.company_id
                WHERE w.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Retirer une offre de la wish list
    public function remove($user_id, $offer_id) {
        $stmt = $this->db->prepare("DELETE FROM wishlist WHERE user_id = ? AND offer_id = ?");
        return $stmt->execute([$user_id, $offer_id]);
    }
}
