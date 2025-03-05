<?php
// app/Models/Evaluation.php

class Evaluation {
    private $db;
    
    // Constructeur qui reçoit l'objet PDO
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Crée une nouvelle évaluation.
     *
     * @param int    $user_id     L'identifiant de l'utilisateur qui évalue.
     * @param int    $company_id  L'identifiant de l'entreprise évaluée.
     * @param int    $rating      La note attribuée (entre 1 et 5).
     * @param string $comments    Les commentaires (facultatif).
     * @return bool               Retourne true en cas de succès.
     */
    public function create($user_id, $company_id, $rating, $comments) {
        $stmt = $this->db->prepare("INSERT INTO evaluations (user_id, company_id, rating, comments) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $company_id, $rating, $comments]);
    }
    
    /**
     * Vérifie si une évaluation existe déjà pour un utilisateur donné sur une entreprise donnée.
     *
     * @param int $user_id     L'identifiant de l'utilisateur.
     * @param int $company_id  L'identifiant de l'entreprise.
     * @return mixed           Retourne un tableau associatif si une évaluation existe, sinon false.
     */
    public function exists($user_id, $company_id) {
        $stmt = $this->db->prepare("SELECT evaluation_id FROM evaluations WHERE user_id = ? AND company_id = ?");
        $stmt->execute([$user_id, $company_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Met à jour une évaluation existante.
     *
     * @param int    $evaluation_id L'identifiant de l'évaluation à mettre à jour.
     * @param int    $rating        La nouvelle note.
     * @param string $comments      Les nouveaux commentaires.
     * @return bool                 Retourne true en cas de succès.
     */
    public function update($evaluation_id, $rating, $comments) {
        $stmt = $this->db->prepare("UPDATE evaluations SET rating = ?, comments = ? WHERE evaluation_id = ?");
        return $stmt->execute([$rating, $comments, $evaluation_id]);
    }
}
