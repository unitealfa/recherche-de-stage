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
    
    // Afficher la liste des offres de stage
    public function index() {
        $offerModel = new Offer($this->db);
        
        // Récupérer les critères de recherche depuis GET
        $company = isset($_GET['company']) ? trim($_GET['company']) : '';
        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $competencies = isset($_GET['competencies']) ? trim($_GET['competencies']) : '';
        
        // Si au moins un critère est renseigné, utiliser la recherche multi-critères
        if ($company !== '' || $title !== '' || $competencies !== '') {
            $offers = $offerModel->searchByCriteria($company, $title, $competencies);
        } else {
            $offers = $offerModel->getAllWithCompanyName();
        }
        
        include "app/Views/offer/index.php";
    }
    
    // Autres méthodes : show, create, edit, delete, etc.
    
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=offer&action=index");
            exit;
        }
        $offer = (new Offer($this->db))->findById($id);
        include "app/Views/offer/show.php";
    }
    
// ...
public function create() {
    // Autoriser ADMIN et PILOTE
    $this->requireRole(['ADMIN', 'PILOTE']);
    
    // Récupérer la liste des entreprises existantes
    require_once "app/Models/Company.php";
    $companyModel = new Company($this->db);
    $companies = $companyModel->getAll(); // Cette méthode doit renvoyer company_id et name
    
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
    
    // Transmettez $companies à la vue
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

    public function getAllWithCompanyName() {
        $sql = "SELECT o.offer_id, o.title, o.company_id, o.description, o.competencies,
                       o.remuneration_base, o.start_date, o.end_date, c.name AS company_name
                FROM offers o 
                INNER JOIN companies c ON o.company_id = c.company_id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
