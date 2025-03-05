<?php
// app/Controllers/WishlistController.php
require_once "app/Controllers/BaseController.php";
require_once "app/Models/Wishlist.php";

class WishlistController extends BaseController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Afficher la wish list de l'étudiant (détails complets)
    public function index() {
        $this->requireRole(['ETUDIANT', 'ADMIN']);
        $wishlistModel = new Wishlist($this->db);
        $wishListItems = $wishlistModel->getDetailsByUserId($_SESSION['user']['user_id']);
        include "app/Views/wishlist/index.php";
    }
    
    // Ajouter une offre à la wish list (vérifier qu'elle n'est pas déjà présente)
    public function add() {
        $this->requireRole(['ETUDIANT', 'ADMIN']);
        $offer_id = $_GET['offer_id'] ?? null;
        if (!$offer_id) {
            header("Location: index.php?controller=offer&action=index");
            exit;
        }
        $wishlistModel = new Wishlist($this->db);
        // Vérifier si l'élément existe déjà
        if ($wishlistModel->exists($_SESSION['user']['user_id'], $offer_id)) {
            header("Location: index.php?controller=wishlist&action=index");
            exit;
        }
        $result = $wishlistModel->add($_SESSION['user']['user_id'], $offer_id);
        if ($result) {
            header("Location: index.php?controller=wishlist&action=index");
            exit;
        } else {
            echo "Erreur lors de l'ajout à la wish list.";
        }
    }
    
    // Retirer une offre de la wish list
    public function remove() {
        $this->requireRole(['ETUDIANT', 'ADMIN']);
        $offer_id = $_GET['offer_id'] ?? null;
        if (!$offer_id) {
            header("Location: index.php?controller=wishlist&action=index");
            exit;
        }
        $wishlistModel = new Wishlist($this->db);
        $wishlistModel->remove($_SESSION['user']['user_id'], $offer_id);
        header("Location: index.php?controller=wishlist&action=index");
        exit;
    }
}
