<?php
// ===================================================================
// Fichier : app/Controllers/CandidatureController.php
// Description : Contrôleur pour gérer les candidatures.
// Ce contrôleur permet d'afficher la liste des candidatures, d'ajouter
// (postuler) une candidature, de modifier, supprimer et afficher les
// détails d'une candidature.
// Pour ce contrôleur, nous autorisons les rôles ETUDIANT et ADMIN pour
// l'affichage et l'ajout, tandis que les actions de modification, suppression
// et consultation détaillée sont réservées à l'ADMIN.
// ===================================================================

// Inclusion du contrôleur de base et des modèles nécessaires.
require_once "app/Controllers/BaseController.php";
require_once "app/Models/Offer.php";      // Pour accéder aux informations sur les offres.
require_once "app/Models/Company.php";    // Pour accéder aux informations sur les entreprises.

// Déclaration de la classe CandidatureController.
class CandidatureController extends BaseController {
    private $db;
    
    // ------------------------------
    // Constructeur
    // ------------------------------
    public function __construct($db) {
        $this->db = $db;
        // Démarrer la session si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // ------------------------------
    // Méthode : index()
    // Description : Affiche la liste des candidatures.
    // - Pour un ADMIN : récupère toutes les candidatures.
    // - Pour un ETUDIANT : récupère uniquement ses candidatures.
    // De plus, on construit deux tableaux associatifs :
    //    $offersMap    : [offer_id] => offre (avec title, company_id, etc.)
    //    $companiesMap : [company_id] => nom de l'entreprise.
    // Ces données sont transmises à la vue.
    // ------------------------------
    public function index() {
        $this->requireRole(['ETUDIANT', 'ADMIN', 'PILOTE']);        
        require_once "app/Models/Candidature.php";
        $candidatureModel = new Candidature($this->db);
        
        // Si l'utilisateur est ADMIN ou PILOTE, récupérer toutes les candidatures,
        // sinon (pour ETUDIANT) ne récupérer que ses candidatures.
        if (in_array(strtoupper($_SESSION['user']['role']), ['ADMIN', 'PILOTE'])) {
            $candidatures = $candidatureModel->getAllWithCandidateOfferAndCompany();
        } else {
            $user_id = $_SESSION['user']['user_id'];
            $candidatures = $candidatureModel->getByUserId($user_id);
        }
        
        include "app/Views/candidature/index.php";
    }
    
    
    
    
    // ------------------------------
    // Méthode : postuler()
    // Description : Permet d'ajouter une candidature.
    // Autorisé pour ETUDIANT et ADMIN.
    // On récupère la liste des offres triées par titre pour le formulaire.
    // ------------------------------
// Dans app/Controllers/CandidatureController.php, dans la méthode postuler()
public function postuler() {
    // Autoriser ETUDIANT et ADMIN à postuler
    $this->requireRole(['ETUDIANT', 'ADMIN']);
    
    $offerModel = new Offer($this->db);
    $offers = $offerModel->getAllSortedByTitle();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $offer_id = trim($_POST['offer_id'] ?? '');
        
        if (empty($offer_id)) {
            $error = "Veuillez sélectionner une offre valide.";
            include "app/Views/candidature/postuler.php";
            return;
        }
        
        $cv_path = "";
        $motivationLetterPath = "";
        
        // Upload du CV
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $upload_dir = "public/uploads/";
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $cv_name = basename($_FILES['cv']['name']);
            $cv_path = $upload_dir . $cv_name;
            move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
        }
        
        // Upload de la lettre de motivation
        if (isset($_FILES['motivation_letter']) && $_FILES['motivation_letter']['error'] == 0) {
            $upload_dir = "public/uploads/";
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $motivationLetterName = basename($_FILES['motivation_letter']['name']);
            $motivationLetterPath = $upload_dir . $motivationLetterName;
            move_uploaded_file($_FILES['motivation_letter']['tmp_name'], $motivationLetterPath);
        }
        
        // Date de candidature (date du jour)
        $date_candidature = date("Y-m-d");
        
        // Récupérer l'ID et le nom de l'utilisateur depuis la session
        $user_id = $_SESSION['user']['user_id'];
        // Vous pouvez également utiliser la concaténation du prénom et nom
        $candidateName = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
        
        require_once "app/Models/Candidature.php";
        $candidatureModel = new Candidature($this->db);
        $result = $candidatureModel->create($user_id, $offer_id, $date_candidature, $cv_path, $motivationLetterPath, $candidateName);
        
        if ($result) {
            header("Location: index.php?controller=candidature&action=index");
            exit;
        } else {
            $error = "Erreur lors de la candidature.";
        }
    }
    
    include "app/Views/candidature/postuler.php";
}


    
    
    


    public function remove() {
        // Autoriser ETUDIANT et ADMIN à retirer une candidature
        $this->requireRole(['ETUDIANT', 'ADMIN']);
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "Aucun ID de candidature fourni.";
            exit;
        }
        
        require_once "app/Models/Candidature.php";
        $candidatureModel = new Candidature($this->db);
        $candidature = $candidatureModel->findById($id);
        
        // Pour les étudiants, on vérifie que la candidature leur appartient.
        if (strtoupper($_SESSION['user']['role']) === 'ETUDIANT') {
            if (!$candidature || $candidature['user_id'] != $_SESSION['user']['user_id']) {
                header("Location: index.php?controller=candidature&action=index");
                exit;
            }
        }
        
        if ($candidatureModel->delete($id)) {
            header("Location: index.php?controller=candidature&action=index");
            exit;
        } else {
            echo "Erreur lors de la suppression.";
            exit;
        }
    }
    
    
    

    
    
    // ------------------------------
    // Méthode : edit()
    // Description : Permet à l'ADMIN de modifier une candidature.
    // ------------------------------
    public function edit() {
        // Restreindre l'accès aux ADMIN uniquement.
        $this->requireRole(['ADMIN']);
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=candidature&action=index");
            exit;
        }
        
        $candidatureModel = new Candidature($this->db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivation_letter = trim($_POST['motivation_letter'] ?? '');
            $result = $candidatureModel->update($id, $motivation_letter);
            if ($result) {
                header("Location: index.php?controller=candidature&action=index");
                exit;
            } else {
                $error = "Erreur lors de la mise à jour de la candidature.";
            }
        }
        
        $candidature = $candidatureModel->findById($id);
        include "app/Views/candidature/edit.php";
    }
    
    // ------------------------------
    // Méthode : delete()
    // Description : Permet à l'ADMIN de supprimer une candidature.
    // ------------------------------
    public function delete() {
        // Restreindre l'accès aux ADMIN uniquement.
        $this->requireRole(['ADMIN']);
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $candidatureModel = new Candidature($this->db);
            $candidatureModel->delete($id);
        }
        header("Location: index.php?controller=candidature&action=index");
        exit;
    }
    
    // ------------------------------
    // Méthode : show()
    // Description : Permet à l'ADMIN d'afficher les détails d'une candidature.
    // ------------------------------
    public function show() {
        // Restreindre l'accès aux ADMIN uniquement.
        $this->requireRole(['ADMIN']);
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=candidature&action=index");
            exit;
        }
        
        $candidatureModel = new Candidature($this->db);
        $candidature = $candidatureModel->findById($id);
        include "app/Views/candidature/show.php";
    }
}
