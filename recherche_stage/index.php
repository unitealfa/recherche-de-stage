<?php

session_start();

require_once "config/database.php";
require_once "config/app.php";

// Autoloader pour les Controllers et Models
spl_autoload_register(function($class) {
    if(file_exists("app/Controllers/" . $class . ".php")){
        require_once "app/Controllers/" . $class . ".php";
    } elseif(file_exists("app/Models/" . $class . ".php")){
        require_once "app/Models/" . $class . ".php";
    }
});

// Détermination du controller et de l'action
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$controllerName = ucfirst($controller) . "Controller";

if(class_exists($controllerName)) {
    $ctrl = new $controllerName($db);
    if(method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "Action '$action' non trouvée dans le controller '$controllerName'.";
    }
} else {
    echo "Controller '$controllerName' non trouvé.";
}
