<?php
// app/Controllers/OfferController.php

require_once "app/Controllers/BaseController.php";
require_once "app/Models/Offer.php";
require_once "app/Models/Company.php";

class OfferController extends BaseController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Afficher la liste des offres de stage avec pagination et recherche multi-critères
    public function index() {
        $offerModel = new Offer($this->db);
        
        // Récupérer les critères de recherche depuis GET
        $company = isset($_GET['company']) ? trim($_GET['company']) : '';
        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $competencies = isset($_GET['competencies']) ? trim($_GET['competencies']) : '';
        
        // Configuration de la pagination : 10 offres par page
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) { 
            $page = 1; 
        }
        $offset = ($page - 1) * $limit;
        
        if ($company !== '' || $title !== '' || $competencies !== '') {
            // Recherche multi-critères
            $allOffers = $offerModel->searchByCriteria($company, $title, $competencies);
            $totalOffers = count($allOffers);
            // Découper le tableau pour obtenir les résultats de la page courante
            $offers = array_slice($allOffers, $offset, $limit);
        } else {
            // Récupérer les offres paginées depuis la base
            $offers = $offerModel->getOffersWithLimit($limit, $offset);
            $totalOffers = $offerModel->getTotalOffers();
        }
        
        $totalPages = ceil($totalOffers / $limit);
        
        include "app/Views/offer/index.php";
    }
    
    // Afficher le détail d'une offre
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=offer&action=index");
            exit;
        }
        $offerModel = new Offer($this->db);
        $offer = $offerModel->findById($id);
        include "app/Views/offer/show.php";
    }
    
    // Créer une nouvelle offre (ADMIN et PILOTE uniquement)
    public function create() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        
        // Récupérer la liste des entreprises existantes
        $companyModel = new Company($this->db);
        $companies = $companyModel->getAll(); // Renvoie company_id et name
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $company_id        = trim($_POST['company_id'] ?? '');
            $title             = trim($_POST['title'] ?? '');
            $description       = trim($_POST['description'] ?? '');
            $competencies      = trim($_POST['competencies'] ?? '');
            $remuneration_base = $_POST['remuneration_base'] ?? 0;
            $start_date        = $_POST['start_date'] ?? '';
            $end_date          = $_POST['end_date'] ?? '';
            
            $offerModel = new Offer($this->db);
            $result = $offerModel->create($company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date);
            
            if ($result) {
                header("Location: index.php?controller=offer&action=index");
                exit;
            } else {
                $error = "Erreur lors de la création de l'offre.";
            }
        }
        
        include "app/Views/offer/create.php";
    }
    
    // Modifier une offre (ADMIN et PILOTE uniquement)
    public function edit() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $offerModel = new Offer($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id                = $_POST['id'] ?? '';
            $company_id        = trim($_POST['company_id'] ?? '');
            $title             = trim($_POST['title'] ?? '');
            $description       = trim($_POST['description'] ?? '');
            $competencies      = trim($_POST['competencies'] ?? '');
            $remuneration_base = $_POST['remuneration_base'] ?? 0;
            $start_date        = $_POST['start_date'] ?? '';
            $end_date          = $_POST['end_date'] ?? '';
            
            $result = $offerModel->update($id, $company_id, $title, $description, $competencies, $remuneration_base, $start_date, $end_date);
            if ($result) {
                header("Location: index.php?controller=offer&action=index");
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        } else {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: index.php?controller=offer&action=index");
                exit;
            }
            $offer = $offerModel->findById($id);
        }
        include "app/Views/offer/edit.php";
    }
    
    // Supprimer une offre (ADMIN et PILOTE uniquement)
    public function delete() {
        $this->requireRole(['ADMIN', 'PILOTE']);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $offerModel = new Offer($this->db);
            $offerModel->delete($id);
        }
        header("Location: index.php?controller=offer&action=index");
        exit;
    }
    
    // Méthode de contrôle d'accès (si non héritée de BaseController)
    protected function requireRole(array $allowedRoles) {
        if (!isset($_SESSION['user']) || !in_array(strtoupper($_SESSION['user']['role']), array_map('strtoupper', $allowedRoles))) {
            header("Location: index.php?controller=error&action=forbidden");
            exit;
        }
    }
}
?>
