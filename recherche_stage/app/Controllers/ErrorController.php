<?php
// app/Controllers/ErrorController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ErrorController {
    public function forbidden() {
        include "app/Views/error/403.php";
    }
    
    public function notFound() {
        include "app/Views/error/404.php";
    }

    public function error999() {
        include "app/Views/error/999.php";
    }

}
