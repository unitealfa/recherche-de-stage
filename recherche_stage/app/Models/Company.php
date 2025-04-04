<?php
// app/Models/Company.php
class Company {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Modification de create() pour inclure la pfp
    public function create($name, $description, $email_contact, $phone_contact, $profile_picture) {
        $stmt = $this->db->prepare("INSERT INTO companies (name, description, email_contact, phone_contact, profile_picture) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $email_contact, $phone_contact, $profile_picture]);
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE company_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Modification de getAll() pour récupérer aussi la pfp
    public function getAll() {
        $stmt = $this->db->query("SELECT company_id, name, description, email_contact, phone_contact, profile_picture FROM companies");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Met à jour la note moyenne de l'entreprise
     * en calculant la moyenne de toutes les évaluations (rating)
     * dans la table evaluations pour une entreprise donnée.
     *
     * @param int $company_id L'identifiant de l'entreprise.
     * @return bool Retourne true en cas de succès.
     */
    public function updateAverageRating($company_id) {
        $stmt = $this->db->prepare("SELECT AVG(rating) AS avg_rating FROM evaluations WHERE company_id = ?");
        $stmt->execute([$company_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $average = $result['avg_rating'] ?? 0;
        
        $updateStmt = $this->db->prepare("UPDATE companies SET average_rating = ? WHERE company_id = ?");
        return $updateStmt->execute([$average, $company_id]);
    }

    /**
     * Recherche toutes les entreprises et trie les résultats de sorte que
     * celles dont le nom contient le terme recherché apparaissent en premier.
     *
     * @param string $name Le terme de recherche.
     * @return array Tableau associatif des entreprises triées.
     */
    public function searchOrderByMatch($name) {
        $sql = "SELECT company_id, name, description, email_contact, phone_contact, profile_picture 
                FROM companies 
                ORDER BY (CASE WHEN name LIKE ? THEN 0 ELSE 1 END), name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Modification de update() pour inclure la pfp
    public function update($id, $name, $description, $email_contact, $phone_contact, $profile_picture) {
        $stmt = $this->db->prepare("UPDATE companies SET name = ?, description = ?, email_contact = ?, phone_contact = ?, profile_picture = ? WHERE company_id = ?");
        return $stmt->execute([$name, $description, $email_contact, $phone_contact, $profile_picture, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM companies WHERE company_id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllSortedByName() {
        $stmt = $this->db->query("SELECT * FROM companies ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modification de search() pour inclure la pfp
    public function search($name) {
        $stmt = $this->db->prepare("SELECT company_id, name, description, email_contact, phone_contact, profile_picture FROM companies WHERE name LIKE ?");
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCompaniesWithLimit($limit, $offset) {
        $stmt = $this->db->prepare("SELECT company_id, name, description, email_contact, phone_contact, profile_picture FROM companies LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir le nombre total d'entreprises
    public function getTotalCompanies() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM companies");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

}
?>
