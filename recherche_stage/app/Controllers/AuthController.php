<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function login() {
        if (isset($_SESSION['user'])) {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $userModel = new User($this->db);
            $user = $userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'role'       => strtoupper($user['role'])
                ];

                // Afficher directement la pop-up ici
                echo "<script>
                    var consent = confirm('Acceptez-vous un cookie ?');
                    if (consent) {
                        window.location.href = 'index.php?controller=auth&action=setRememberCookie';
                    } else {
                        window.location.href = 'index.php?controller=dashboard&action=index';
                    }
                </script>";
                exit;
                
            } else {
                $error = "Email ou mot de passe incorrect.";
                include "app/Views/auth/login.php";
            }
        } else {
            include "app/Views/auth/login.php";
        }
    }

    public function setRememberCookie() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        $secretKey = 'cesicesiboom';
        $token = hash('sha256', $user['user_id'] . $user['email'] . $secretKey);
        
        $cookieData = json_encode([
            'user_id' => $user['user_id'],
            'token'   => $token
        ]);
        
        $cookieValue = base64_encode($cookieData);
        setcookie('remember_me', $cookieValue, time() + (86400 * 30), "/");
        
        header("Location: index.php?controller=dashboard&action=index");
        exit;
    }
    
    // Page de consentement aux cookies (affichage via pop-up dans le formulaire)
    // Cette méthode n'est plus utilisée, puisque le consentement est demandé via la pop-up dans login.php.
    // Vous pouvez supprimer ou ignorer cette méthode si vous ne souhaitez pas afficher une page séparée.
    public function cookieConsent() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        // Si le consentement a déjà été traité, rediriger vers le dashboard
        if (isset($_SESSION['cookie_consent']) && $_SESSION['cookie_consent'] === true) {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
        
        // Si le formulaire de consentement est soumis (dans le cas d'une page de consentement)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $choice = $_POST['accept_cookies'] ?? 'no';
            
            // Marquer dans la session que le consentement a été traité
            $_SESSION['cookie_consent'] = true;
            
            if ($choice === 'yes') {
                $user = $_SESSION['user'];
                $secretKey = 'cesicesiboom';
                $token = hash('sha256', $user['user_id'] . $user['email'] . $secretKey);
                
                $cookieData = json_encode([
                    'user_id' => $user['user_id'],
                    'token'   => $token
                ]);
                $cookieValue = base64_encode($cookieData);
                setcookie('remember_me', $cookieValue, time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['remember_me'])) {
                    setcookie('remember_me', '', time() - 3600, "/");
                }
            }
            
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
        
        // Afficher la vue de consentement (si vous souhaitez l'utiliser)
        include "app/Views/auth/cookie_consent.php";
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
            
            $userModel = new User($this->db);
            if ($userModel->findByEmail($email)) {
                $error = "Cet email est déjà utilisé.";
                include "app/Views/auth/register.php";
                return;
            }
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $result = $userModel->create($first_name, $last_name, $email, $password_hash, 'ETUDIANT');
            
            if ($result) {
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
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
        }
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}
