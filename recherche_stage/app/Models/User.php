<?php
// app/Models/User.php
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Méthode create() acceptant un 6ème paramètre : profilePicture
    public function create($first_name, $last_name, $email, $password_hash, $role = 'ETUDIANT', $profilePicture = null) {
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$first_name, $last_name, $email, $password_hash, $role, $profilePicture]);
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
    
    public function update($id, $first_name, $last_name, $email, $role, $profilePicture = null) {
        if ($profilePicture !== null) {
            $stmt = $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ?, profile_picture = ? WHERE user_id = ?");
            return $stmt->execute([$first_name, $last_name, $email, $role, $profilePicture, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE user_id = ?");
            return $stmt->execute([$first_name, $last_name, $email, $role, $id]);
        }
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function searchByNameOrEmail($search) {
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
    
    // Méthode pour récupérer les utilisateurs avec une limite et un offset (pour la pagination)
    public function getUsersWithLimit($limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
        // Utilisation de bindValue avec type integer pour plus de sécurité
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Méthode pour obtenir le nombre total d'utilisateurs
    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
