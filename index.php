<?php
session_start();

require_once "config/database.php";
require_once "config/app.php";

// Vérification du cookie si l'utilisateur n'est pas déjà connecté
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    $cookieData = base64_decode($_COOKIE['remember_me']);
    $data = json_decode($cookieData, true);
    
    if (isset($data['user_id'], $data['token'])) {
        require_once "app/Models/User.php";
        $userModel = new User($db); // $db défini dans config/database.php
        $user = $userModel->findById($data['user_id']);
        
        if ($user) {
            $secretKey = 'cesicesiboom';
            $expectedToken = hash('sha256', $user['user_id'] . $user['email'] . $secretKey);
            
            if (hash_equals($expectedToken, $data['token'])) {
                // Recréer la session automatiquement
                $_SESSION['user'] = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'role'       => strtoupper($user['role'])
                ];
            }
        }
    }
}

// Autoloader pour charger automatiquement les classes (Controllers et Models)
spl_autoload_register(function($class) {
    if (file_exists("app/Controllers/" . $class . ".php")) {
        require_once "app/Controllers/" . $class . ".php";
    } elseif (file_exists("app/Models/" . $class . ".php")) {
        require_once "app/Models/" . $class . ".php";
    }
});

// Détermination du contrôleur et de l'action à partir des paramètres GET
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action     = isset($_GET['action']) ? $_GET['action'] : 'login';

$controllerName = ucfirst($controller) . "Controller";

if (class_exists($controllerName)) {
    $ctrl = new $controllerName($db);
    if (method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "Action '$action' non trouvée dans le contrôleur '$controllerName'.";
    }
} else {
    echo "Contrôleur '$controllerName' non trouvé.";
}
