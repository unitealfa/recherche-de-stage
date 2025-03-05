<?php
// app/Controllers/DashboardController.php
require_once "app/Controllers/BaseController.php";

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
    
    public function admin() {
        // Vue du Dashboard Administrateur
        include "app/Views/dashboard/admin.php";
    }
    
    public function etudiant() {
        // Vue du Dashboard Étudiant
        include "app/Views/dashboard/etudiant.php";
    }
    
    // Ajoutez cette méthode pour le pilote
    public function pilote() {
        // Vue du Dashboard Pilote
        include "app/Views/dashboard/pilote.php";
    }
}
