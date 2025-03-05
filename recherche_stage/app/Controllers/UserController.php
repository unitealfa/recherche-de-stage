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
    
    // Afficher la liste des utilisateurs (accessible à ADMIN et PILOTE)
    public function index() {
        // Autoriser l'accès à ADMIN et PILOTE
        $this->requireRole(['ADMIN', 'PILOTE']);
    
        // Récupérer la recherche
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
        // Récupérer le rôle de l'utilisateur connecté
        $currentUserRole = strtoupper($_SESSION['user']['role'] ?? '');
    
        // Instancier le modèle User
        $userModel = new User($this->db);
    
        // Si c'est un PILOTE, on ne veut que les étudiants,
        // et si "alpha" est tapé dans la barre de recherche,
        // on renvoie directement l'erreur 999
        if ($currentUserRole === 'PILOTE') {
            // Si la chaîne "alpha" est détectée dans le search,
            // on redirige vers l'erreur 999
            if (stripos($search, 'alpha') !== false) {
                header("Location: index.php?controller=error&action=error999");
                exit;
            }
            // On récupère uniquement les rôles ETUDIANT
            $users = $userModel->searchByNameOrEmailAndRole($search, 'ETUDIANT');
        } else {
            // Sinon (ADMIN), on récupère tout
            $users = $userModel->searchByNameOrEmail($search);
        }
    
        include "app/Views/user/index.php";
    }
    

    
    
    // Afficher le détail d'un utilisateur
    public function show() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $id = $_GET['id'] ?? null;
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
        // Autoriser ADMIN et PILOTE
        $this->requireRole(['ADMIN','PILOTE']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name  = trim($_POST['last_name']  ?? '');
            $email      = trim($_POST['email']      ?? '');
            $password   = $_POST['password']        ?? '';
            
            if (strtoupper($_SESSION['user']['role']) === 'ADMIN') {
                // Si on veut laisser l’admin choisir le rôle, on peut afficher un <select> dans la vue
                $role = $_POST['role'] ?? 'ETUDIANT';
            } else {
                // Si c'est un PILOTE, forcer ETUDIANT
                $role = 'ETUDIANT';
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
            $id         = $_POST['id'] ?? '';
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name  = trim($_POST['last_name'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $role       = $_POST['role'] ?? 'ETUDIANT';
            
            // Si l'utilisateur connecté est PILOTE, forcer le rôle à ETUDIANT
            if ($_SESSION['user']['role'] === 'PILOTE') {
                $role = 'ETUDIANT';
            }
            
            $result = $userModel->update($id, $first_name, $last_name, $email, $role);
            if ($result) {
                header("Location: index.php?controller=user&action=index");
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        } else {
            $id = $_GET['id'] ?? null;
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
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userModel = new User($this->db);
            $userModel->delete($id);
        }
        header("Location: index.php?controller=user&action=index");
        exit;
    }
}
