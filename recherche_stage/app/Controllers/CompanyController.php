<?php
// app/Controllers/CompanyController.php

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
    
    // Afficher la liste des entreprises avec pagination et recherche
    public function index() {
        $companyModel = new Company($this->db);
        
        // Récupérer le terme de recherche (optionnel)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Configuration de la pagination : 10 entreprises par page
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) { $page = 1; }
        $offset = ($page - 1) * $limit;
        
        if (!empty($search)) {
            // Utiliser la méthode de recherche existante pour filtrer par nom
            $allCompanies = $companyModel->searchOrderByMatch($search);
            $totalCompanies = count($allCompanies);
            // Découper le tableau pour obtenir les résultats de la page courante
            $companies = array_slice($allCompanies, $offset, $limit);
        } else {
            // Récupérer les entreprises paginées directement depuis la base
            $companies = $companyModel->getCompaniesWithLimit($limit, $offset);
            $totalCompanies = $companyModel->getTotalCompanies();
        }
        
        $totalPages = ceil($totalCompanies / $limit);
        
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
    
    // Créer une entreprise (accessible à ADMIN et PILOTE)
    public function create() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $email_contact = trim($_POST['email_contact'] ?? '');
            $phone_contact = trim($_POST['phone_contact'] ?? '');
            
            // Traitement de l'upload de l'image de profil
            $uploadDir = "public/uploads/companies/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                    $profile_picture = $fileName;
                } else {
                    $profile_picture = "default_pfp.png";
                }
            } else {
                $profile_picture = "default_pfp.png";
            }
            
            $companyModel = new Company($this->db);
            $result = $companyModel->create($name, $description, $email_contact, $phone_contact, $profile_picture);
            
            if ($result) {
                header("Location: index.php?controller=company&action=index");
                exit;
            } else {
                $error = "Erreur lors de la création de l'entreprise.";
            }
        }
        
        include "app/Views/company/create.php";
    }
    
    // Modifier une entreprise (accessible à ADMIN et PILOTE)
    public function edit() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $companyModel = new Company($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $email_contact = trim($_POST['email_contact'] ?? '');
            $phone_contact = trim($_POST['phone_contact'] ?? '');
            
            // Traitement de l'upload de l'image de profil
            $uploadDir = "public/uploads/companies/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                    $profile_picture = $fileName;
                } else {
                    $profile_picture = $_POST['existing_profile_picture'] ?? "default_pfp.png";
                }
            } else {
                $profile_picture = $_POST['existing_profile_picture'] ?? "default_pfp.png";
            }
            
            $result = $companyModel->update($id, $name, $description, $email_contact, $phone_contact, $profile_picture);
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
    
    // Supprimer une entreprise (accessible à ADMIN et PILOTE)
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
    
    // Évaluer une entreprise (accessible à ADMIN, PILOTE et ETUDIANT)
    public function evaluate() {
        $this->requireRole(['ADMIN', 'PILOTE', 'ETUDIANT']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $company_id = $_POST['company_id'] ?? '';
            $rating = $_POST['rating'] ?? '';
            $comments = $_POST['comments'] ?? '';
            
            require_once "app/Models/Evaluation.php";
            $evaluationModel = new Evaluation($this->db);
            
            $existingEvaluation = $evaluationModel->exists($_SESSION['user']['user_id'], $company_id);
            
            if ($existingEvaluation) {
                $result = $evaluationModel->update($existingEvaluation['evaluation_id'], $rating, $comments);
            } else {
                $result = $evaluationModel->create($_SESSION['user']['user_id'], $company_id, $rating, $comments);
            }
            
            if ($result) {
                require_once "app/Models/Company.php";
                $companyModel = new Company($this->db);
                $companyModel->updateAverageRating($company_id);
                
                header("Location: index.php?controller=company&action=show&id=" . $company_id);
                exit;
            } else {
                $error = "Erreur lors de l'évaluation.";
            }
        }
        header("Location: index.php?controller=company&action=show&id=" . ($_POST['company_id'] ?? ''));
        exit;
    }
    
    // Méthode de contrôle d'accès (si non déjà héritée de BaseController)
    protected function requireRole(array $allowedRoles) {
        if (!isset($_SESSION['user']) || !in_array(strtoupper($_SESSION['user']['role']), array_map('strtoupper', $allowedRoles))) {
            header("Location: index.php?controller=error&action=forbidden");
            exit;
        }
    }
}
?>
