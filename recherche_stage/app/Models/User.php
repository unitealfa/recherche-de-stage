<?php
// app/Models/User.php
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create($first_name, $last_name, $email, $password_hash, $role = 'ETUDIANT') {
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$first_name, $last_name, $email, $password_hash, $role]);
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllEtudiants() {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'ETUDIANT'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $first_name, $last_name, $email, $role) {
        $stmt = $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE user_id = ?");
        return $stmt->execute([$first_name, $last_name, $email, $role, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    public function searchByNameOrEmail($search) {
        // Rechercher dans first_name, last_name, email, sur tous les rôles
        $sql = "SELECT * FROM users
                WHERE (first_name LIKE :search
                   OR last_name LIKE :search
                   OR email LIKE :search)";
        $stmt = $this->db->prepare($sql);
        $param = '%' . $search . '%';
        $stmt->bindParam(':search', $param);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchByNameOrEmailAndRole($search, $roleWanted) {
        // Rechercher uniquement dans un rôle précis (ETUDIANT)
        $sql = "SELECT * FROM users
                WHERE role = :roleWanted
                  AND (first_name LIKE :search
                    OR last_name LIKE :search
                    OR email LIKE :search)";
        $stmt = $this->db->prepare($sql);
        $param = '%' . $search . '%';
        $stmt->bindParam(':search', $param);
        $stmt->bindParam(':roleWanted', $roleWanted);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
