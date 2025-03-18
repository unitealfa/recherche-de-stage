<?php
// app/Views/layout/header.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Déterminer le contrôleur et l'action courants à partir des paramètres GET
$currentController = $_GET['controller'] ?? '';
$currentAction = $_GET['action'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de Stage</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/main.css?v=<?= time(); ?>">

</head>
<body>
<header>
    <h1>Recherche de Stage</h1>
    <nav>
        <?php if (isset($_SESSION['user'])): ?>
            <span>Bienvenue, <?= htmlspecialchars($_SESSION['user']['first_name']); ?></span>
            <?php 
                $role = strtoupper($_SESSION['user']['role']);
                if ($role === 'ADMIN') {
                    echo '<a href="index.php?controller=dashboard&action=admin">Dashboard Admin</a>';
                } elseif ($role === 'ETUDIANT') {
                    echo '<a href="index.php?controller=dashboard&action=etudiant">Dashboard Étudiant</a>';
                } elseif ($role === 'PILOTE') {
                    echo '<a href="index.php?controller=dashboard&action=pilote">Dashboard Pilote</a>';
                }
            ?>
            <a href="index.php?controller=auth&action=logout">Déconnexion</a>
        <?php else: ?>
            <!-- Aucun lien de connexion n'est affiché si l'utilisateur n'est pas connecté -->
        <?php endif; ?>
    </nav>
</header>
<main>