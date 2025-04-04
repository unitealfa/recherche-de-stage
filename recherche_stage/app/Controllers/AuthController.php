<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Connexion de l'utilisateur
    public function login() {
        if (isset($_SESSION['user'])) {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
    
        // Vérifie si un cookie 'remember_me' existe et pré-remplie les champs si nécessaire
        $rememberMeCookie = $_COOKIE['remember_me'] ?? '';
        if ($rememberMeCookie) {
            $cookieData = json_decode(base64_decode($rememberMeCookie), true);
            $email = $cookieData['user_email'] ?? '';
            $password = $cookieData['user_password'] ?? '';
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
    
            $userModel = new User($this->db);
            $user = $userModel->findByEmail($email);
    
            if ($user && password_verify($password, $user['password_hash'])) {
                // Enregistrer les informations de l'utilisateur dans la session
                $_SESSION['user'] = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'role'       => strtoupper($user['role']),
                    'profile_picture' => $user['profile_picture']
                ];
    
                // Sauvegarder l'email et le mot de passe dans un cookie "remember_me" si accepté
                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    setcookie('remember_me', base64_encode(json_encode([
                        'user_email' => $email,
                        'user_password' => $password
                    ])), time() + (86400 * 30), "/"); // Cookie valable 30 jours
                }
    
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
    
    
    // Création d'un cookie "remember me"
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
    
    
    // Inscription de l'utilisateur (avec upload de pfp)
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
    
        // Gestion de l'upload de la photo de profil
        $profilePicture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Ajoutez ici toutes les extensions d'images que vous souhaitez accepter
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = 'public/uploads/profile_pictures/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $profilePicture = $destPath;
                }
            }
        }

    
            // Si aucune image n'est uploadée, laisser $profilePicture à NULL.
            // La vue affichera alors l'image par défaut.
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $result = $userModel->create($first_name, $last_name, $email, $password_hash, 'ETUDIANT', $profilePicture);
    
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
    
    // Déconnexion de l'utilisateur
    public function logout() {
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
        }
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}
