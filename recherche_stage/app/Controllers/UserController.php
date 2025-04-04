<?php
// app/Controllers/UserController.php
require_once "app/Controllers/BaseController.php";
require_once "app/Models/User.php";

class UserController extends BaseController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Afficher la liste des utilisateurs avec pagination (accessible à ADMIN et PILOTE)
    public function index() {
        // Vérifier que l'utilisateur connecté a le droit (ADMIN ou PILOTE)
        $this->requireRole(['ADMIN', 'PILOTE']);
        
        // Récupérer et sécuriser le terme de recherche (optionnel)
        $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?: '';
        
        // Récupérer le rôle de l'utilisateur connecté
        $currentUserRole = strtoupper($_SESSION['user']['role'] ?? '');
    
        // Configuration de la pagination : 10 utilisateurs par page
        $limit = 10;
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $offset = ($page - 1) * $limit;
    
        // Instancier le modèle User
        $userModel = new User($this->db);
    
        if (!empty($search)) {
            if ($currentUserRole === 'PILOTE') {
                // Pour les pilotes, rechercher uniquement dans les comptes étudiants
                $allUsers = $userModel->searchByNameOrEmailAndRole($search, 'ETUDIANT');
            } else {
                // Pour ADMIN, récupérer tous les utilisateurs correspondant à la recherche
                $allUsers = $userModel->searchByNameOrEmail($search);
            }
            $totalUsers = count($allUsers);
            // Découper les résultats pour la page courante
            $users = array_slice($allUsers, $offset, $limit);
        } else {
            // Si aucune recherche
            if ($currentUserRole === 'PILOTE') {
                // Pour les pilotes, récupérer uniquement les comptes étudiants
                $allUsers = $userModel->getAllEtudiants();
                $totalUsers = count($allUsers);
                $users = array_slice($allUsers, $offset, $limit);
            } else {
                // Pour ADMIN, récupérer tous les utilisateurs avec limite et offset
                $users = $userModel->getUsersWithLimit($limit, $offset);
                $totalUsers = $userModel->getTotalUsers();
            }
        }
    
        $totalPages = ceil($totalUsers / $limit);
    
        include "app/Views/user/index.php";
    }
    
    // Afficher le détail d'un utilisateur
    public function show() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: index.php?controller=user&action=index");
            exit;
        }
        $userModel = new User($this->db);
        $user = $userModel->findById($id);
        include "app/Views/user/show.php";
    }
    
    // Créer un utilisateur (ADMIN peut créer tous les comptes, PILOTE uniquement des comptes ETUDIANT)
    public function create() {
        $this->requireRole(['ADMIN', 'PILOTE']);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING) ?: '');
            $last_name  = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING) ?: '');
            $email      = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?: '');
            $password   = $_POST['password'] ?? '';
    
            // Valider l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Adresse email invalide.";
                include "app/Views/user/create.php";
                return;
            }
    
            // Pour ADMIN, récupérer le rôle choisi, sinon imposer "ETUDIANT"
            if (strtoupper($_SESSION['user']['role']) === 'ADMIN') {
                $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) ?: 'ETUDIANT';
            } else {
                $role = 'ETUDIANT';
            }
    
            // Vérifier que le mot de passe est non vide
            if (empty($password)) {
                $error = "Le mot de passe est requis.";
                include "app/Views/user/create.php";
                return;
            }
    
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
            $userModel = new User($this->db);
            $result = $userModel->create($first_name, $last_name, $email, $password_hash, $role);
    
            if ($result) {
                header("Location: index.php?controller=user&action=index");
                exit;
            } else {
                $error = "Erreur lors de la création du compte.";
            }
        }
    
        include "app/Views/user/create.php";
    }
    
    // Modifier un utilisateur (accessible à ADMIN et PILOTE, PILOTE ne peut modifier que des comptes ETUDIANT)
    public function edit() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $userModel = new User($this->db);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $first_name = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING) ?: '');
            $last_name  = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING) ?: '');
            $email      = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?: '');
            $role       = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) ?: 'ETUDIANT';
    
            // Valider l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Adresse email invalide.";
                include "app/Views/user/edit.php";
                return;
            }
    
            // Si l'utilisateur connecté est PILOTE, on force le rôle à "ETUDIANT"
            if ($_SESSION['user']['role'] === 'PILOTE') {
                $role = 'ETUDIANT';
            }
    
            $profilePicture = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
                $fileName = $_FILES['profile_picture']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
    
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $uploadDir = 'public/uploads/profile_pictures/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $profilePicture = $destPath;
                    }
                }
            }
    
            $result = $userModel->update($id, $first_name, $last_name, $email, $role, $profilePicture);
            if ($result) {
                if ($_SESSION['user']['user_id'] == $id && $profilePicture !== null) {
                    $_SESSION['user']['profile_picture'] = $profilePicture;
                }
                header("Location: index.php?controller=user&action=index");
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                header("Location: index.php?controller=user&action=index");
                exit;
            }
            $user = $userModel->findById($id);
        }
        include "app/Views/user/edit.php";
    }
    
    // Supprimer un utilisateur (accessible à ADMIN et PILOTE)
    public function delete() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $userModel = new User($this->db);
            $userModel->delete($id);
        }
        header("Location: index.php?controller=user&action=index");
        exit;
    }
}
?>
