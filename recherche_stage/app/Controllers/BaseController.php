<?php
// app/Controllers/BaseController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class BaseController {
    protected function requireRole(array $allowedRoles) {
        $userRole = isset($_SESSION['user']['role']) ? strtoupper($_SESSION['user']['role']) : '';
        // Convertit tous les rôles autorisés en majuscules pour la comparaison
        $allowedRoles = array_map('strtoupper', $allowedRoles);
        if (!isset($_SESSION['user']) || !in_array($userRole, $allowedRoles)) {
            header("Location: index.php?controller=error&action=forbidden");
            exit;
        }
    }
}
