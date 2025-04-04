<?php
// app/Controllers/DashboardController.php
require_once "app/Controllers/BaseController.php";
require_once "app/Models/Company.php";
require_once "app/Models/Offer.php";
require_once "app/Models/Candidature.php";
require_once "app/Models/User.php";

class DashboardController extends BaseController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Méthode index() qui redirige selon le rôle
    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $role = strtoupper($_SESSION['user']['role']);
        if ($role === 'ADMIN') {
            $this->admin();
        } elseif ($role === 'ETUDIANT') {
            $this->etudiant();
        } elseif ($role === 'PILOTE') {
            $this->pilote();
        } else {
            header("Location: index.php?controller=error&action=forbidden");
            exit;
        }
    }

    // ---------------------------------------------------
    //  Dashboard ADMIN
    // ---------------------------------------------------
    public function admin() {
        $companyModel     = new Company($this->db);
        $offerModel       = new Offer($this->db);
        $candidatureModel = new Candidature($this->db);
        $userModel        = new User($this->db);

        $totalCompanies    = count($companyModel->getAll());
        $totalOffers       = count($offerModel->getAll());
        $totalApplications = count($candidatureModel->getAllWithCandidateOfferAndCompany());
        $totalUsers        = count($userModel->getAll());

        // Pour le graphique (candidatures/offres par mois)
        $applicationsPerMonth = $this->getApplicationsPerMonth($candidatureModel);
        $offersPerMonth       = $this->getOffersPerMonth($offerModel);

        include "app/Views/dashboard/admin.php";
    }

    // ---------------------------------------------------
    //  Dashboard PILOTE
    // ---------------------------------------------------
    public function pilote() {
        $companyModel     = new Company($this->db);
        $offerModel       = new Offer($this->db);
        $candidatureModel = new Candidature($this->db);
        $userModel        = new User($this->db);

        // Statistiques identiques à l'admin pour remplir les 4 cartes
        $totalCompanies    = count($companyModel->getAll());
        $totalOffers       = count($offerModel->getAll());
        $totalApplications = count($candidatureModel->getAllWithCandidateOfferAndCompany());
        $totalUsers        = count($userModel->getAll());

        // Données pour le graphique
        $applicationsPerMonth = $this->getApplicationsPerMonth($candidatureModel);
        $offersPerMonth       = $this->getOffersPerMonth($offerModel);

        include "app/Views/dashboard/pilote.php";
    }

    // ---------------------------------------------------
    //  Dashboard ÉTUDIANT
    // ---------------------------------------------------
    public function etudiant() {
        $companyModel     = new Company($this->db);
        $offerModel       = new Offer($this->db);
        $candidatureModel = new Candidature($this->db);
        $userModel        = new User($this->db);

        // Même logique : on affiche les stats globales
        $totalCompanies    = count($companyModel->getAll());
        $totalOffers       = count($offerModel->getAll());
        $totalApplications = count($candidatureModel->getAllWithCandidateOfferAndCompany());
        $totalUsers        = count($userModel->getAll());

        // Données pour le graphique
        $applicationsPerMonth = $this->getApplicationsPerMonth($candidatureModel);
        $offersPerMonth       = $this->getOffersPerMonth($offerModel);

        include "app/Views/dashboard/etudiant.php";
    }

    // ---------------------------------------------------
    //  Méthodes pour obtenir des statistiques par mois (exemple)
    // ---------------------------------------------------
    private function getApplicationsPerMonth($candidatureModel) {
        $stmt = $this->db->query("
            SELECT MONTH(date_candidature) as month, COUNT(*) as total
            FROM candidatures
            GROUP BY MONTH(date_candidature)
            ORDER BY MONTH(date_candidature)
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getOffersPerMonth($offerModel) {
        $stmt = $this->db->query("
            SELECT MONTH(start_date) as month, COUNT(*) as total
            FROM offers
            GROUP BY MONTH(start_date)
            ORDER BY MONTH(start_date)
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
