<?php
// app/Controllers/AuthController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Page de connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $userModel = new User($this->db);
            $user = $userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // On force la mise en majuscules du rôle pour éviter toute différence de casse
                $_SESSION['user'] = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'role'       => strtoupper($user['role'])  // Convertir en majuscules
                ];
    
                header("Location: index.php?controller=dashboard&action=index");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                include "app/Views/auth/login.php";
            }
        } else {
            include "app/Views/auth/login.php";
        }
    }
    
    // Page d'inscription
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name  = trim($_POST['last_name'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $password   = $_POST['password'] ?? '';
            $confirm    = $_POST['confirm_password'] ?? '';
            
            if ($password !== $confirm) {
                $error = "Les mots de passe ne correspondent pas.";
                include "app/Views/auth/register.php";
                return;
            }
            
            // Vérifier si l'email est déjà utilisé
            $userModel = new User($this->db);
            if ($userModel->findByEmail($email)) {
                $error = "Cet email est déjà utilisé.";
                include "app/Views/auth/register.php";
                return;
            }
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $result = $userModel->create($first_name, $last_name, $email, $password_hash, 'ETUDIANT');
            
            if ($result) {
                // Inscription réussie : redirige vers la page de connexion avec un message de succès
                header("Location: index.php?controller=auth&action=login&message=inscription_reussie");
                exit;
            } else {
                $error = "Erreur lors de l'inscription.";
                include "app/Views/auth/register.php";
            }
        } else {
            include "app/Views/auth/register.php";
        }
    }
    
    // Déconnexion
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}
