<?php
// app/Controllers/CompanyController.php

// Inclusion du contrôleur de base et du modèle Company
require_once "app/Controllers/BaseController.php";
require_once "app/Models/Company.php";

class CompanyController extends BaseController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Afficher la liste des entreprises avec option de recherche
    public function index() {
        $companyModel = new Company($this->db);
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            // Utilisation de la méthode qui trie par pertinence : les entreprises dont le nom correspond
            // apparaissent en premier
            $companies = $companyModel->searchOrderByMatch($search);
        } else {
            $companies = $companyModel->getAll();
        }
        
        include "app/Views/company/index.php";
    }
    
    // Afficher le détail d'une entreprise
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=company&action=index");
            exit;
        }
        $companyModel = new Company($this->db);
        $company = $companyModel->findById($id);
        include "app/Views/company/show.php";
    }
    
    // Créer, modifier et supprimer des entreprises (ADMIN et PILOTE uniquement)
    public function create() {
        // Autoriser uniquement ADMIN et PILOTE
        $this->requireRole(['ADMIN', 'PILOTE']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name          = trim($_POST['name'] ?? '');
            $description   = trim($_POST['description'] ?? '');
            $email_contact = trim($_POST['email_contact'] ?? '');
            $phone_contact = trim($_POST['phone_contact'] ?? '');
            
            $companyModel = new Company($this->db);
            $result = $companyModel->create($name, $description, $email_contact, $phone_contact);
            
            if ($result) {
                header("Location: index.php?controller=company&action=index");
                exit;
            } else {
                $error = "Erreur lors de la création de l'entreprise.";
            }
        }
        
        include "app/Views/company/create.php";
    }
    
    public function edit() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $companyModel = new Company($this->db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $email_contact = trim($_POST['email_contact'] ?? '');
            $phone_contact = trim($_POST['phone_contact'] ?? '');
            
            $result = $companyModel->update($id, $name, $description, $email_contact, $phone_contact);
            if ($result) {
                header("Location: index.php?controller=company&action=index");
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        } else {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: index.php?controller=company&action=index");
                exit;
            }
            $company = $companyModel->findById($id);
        }
        include "app/Views/company/edit.php";
    }
    
    public function delete() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $companyModel = new Company($this->db);
            $companyModel->delete($id);
        }
        header("Location: index.php?controller=company&action=index");
        exit;
    }
    
    // ------------------------------
    // Méthode : evaluate()
    // Description : Permet à un utilisateur (ADMIN, PILOTE ou ETUDIANT) d'évaluer une entreprise.
    // L'utilisateur ne peut évaluer qu'une seule fois ; si une évaluation existe, elle sera mise à jour.
    // Après l'enregistrement de l'évaluation, la moyenne des évaluations de l'entreprise est recalculée.
    // ------------------------------
    public function evaluate() {
        $this->requireRole(['ADMIN', 'PILOTE', 'ETUDIANT']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $company_id = $_POST['company_id'] ?? '';
            $rating = $_POST['rating'] ?? '';
            $comments = $_POST['comments'] ?? '';
            
            // Instancier le modèle Evaluation
            require_once "app/Models/Evaluation.php";
            $evaluationModel = new Evaluation($this->db);
            
            // Vérifier si l'utilisateur a déjà évalué cette entreprise
            $existingEvaluation = $evaluationModel->exists($_SESSION['user']['user_id'], $company_id);
            
            if ($existingEvaluation) {
                // Mettre à jour l'évaluation existante
                $result = $evaluationModel->update($existingEvaluation['evaluation_id'], $rating, $comments);
            } else {
                // Créer une nouvelle évaluation
                $result = $evaluationModel->create($_SESSION['user']['user_id'], $company_id, $rating, $comments);
            }
            
            if ($result) {
                // Mettre à jour la moyenne des évaluations de l'entreprise
                require_once "app/Models/Company.php";
                $companyModel = new Company($this->db);
                $companyModel->updateAverageRating($company_id);
                
                header("Location: index.php?controller=company&action=show&id=" . $company_id);
                exit;
            } else {
                $error = "Erreur lors de l'évaluation.";
            }
        }
        // En cas d'accès direct, rediriger
        header("Location: index.php?controller=company&action=show&id=" . ($_POST['company_id'] ?? ''));
        exit;
    }
    
    // ------------------------------
    // Méthode requireRole()
    // Cette méthode est utilisée pour vérifier que l'utilisateur a un rôle autorisé.
    // Elle est généralement définie dans BaseController, mais peut être redéfinie ici si besoin.
    // ------------------------------
    protected function requireRole(array $allowedRoles) {
        if (!isset($_SESSION['user']) || !in_array(strtoupper($_SESSION['user']['role']), array_map('strtoupper', $allowedRoles))) {
            header("Location: index.php?controller=error&action=forbidden");
            exit;
        }
    }
}
