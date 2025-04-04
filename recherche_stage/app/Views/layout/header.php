<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer le rôle et le prénom de l'utilisateur (si connecté)
$role = isset($_SESSION['user']['role']) ? strtoupper($_SESSION['user']['role']) : '';
$userName = $_SESSION['user']['first_name'] ?? '';

// Récupération du controller/action via $_GET
$currentController = $_GET['controller'] ?? 'auth';
$currentAction     = $_GET['action'] ?? 'login';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de Stage</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/header.css?v=<?= time(); ?>">
</head>
<body>
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>
    <script src="<?= BASE_URL ?>public/js/header.js"></script>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">
            <img src="<?= BASE_URL ?>public/images/LOGOPNG.png" alt="Logo" />
        </div>
        <div class="indicator"></div>
        <!-- Affichage de la sidebar sur toutes les pages, y compris le Dashboard -->
        <ul class="nav-links">
            <!-- Dashboard -->
            <?php if ($role): ?>
            <li>
                <a id="dashboardLink" href="index.php?controller=dashboard&action=<?= strtolower($role) ?>" class="active" data-tooltip="Dashboard">
                    <img src="<?= BASE_URL ?>public/images/1.svg" alt="Dashboard Icon" />
                </a>
            </li>
            <?php endif; ?>
            <!-- Entreprises (ADMIN ou PILOTE) -->
            <?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
            <li>
                <a id="companyLink" href="index.php?controller=company&action=index" data-tooltip="Entreprises">
                    <img src="<?= BASE_URL ?>public/images/6.svg" alt="Entreprises Icon" />
                </a>
            </li>
            <?php endif; ?>
            <!-- Utilisateurs (ADMIN ou PILOTE) -->
            <?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
            <li>
                <a id="userLink" href="index.php?controller=user&action=index" data-tooltip="Utilisateurs">
                    <img src="<?= BASE_URL ?>public/images/5.svg" alt="Users Icon" />
                </a>
            </li>
            <?php endif; ?>
            <!-- Offres (tous les rôles connectés) -->
            <?php if ($role): ?>
            <li>
                <a id="offerLink" href="index.php?controller=offer&action=index" data-tooltip="Offres">
                    <img src="<?= BASE_URL ?>public/images/3.svg" alt="Offres Icon" />
                </a>
            </li>
            <?php endif; ?>
            <!-- Candidatures (ADMIN, PILOTE, ETUDIANT) -->
            <?php if (in_array($role, ['ADMIN', 'PILOTE', 'ETUDIANT'])): ?>
            <li>
                <a id="candidatureLink" href="index.php?controller=candidature&action=index" data-tooltip="Candidatures">
                    <img src="<?= BASE_URL ?>public/images/2.svg" alt="Candidatures Icon" />
                </a>
            </li>
            <?php endif; ?>
            <!-- Wish-list (ADMIN ou ETUDIANT) -->
            <?php if ($role === 'ADMIN' || $role === 'ETUDIANT'): ?>
            <li>
                <a id="wishlistLink" href="index.php?controller=wishlist&action=index" data-tooltip="Wish-list">
                    <img src="<?= BASE_URL ?>public/images/4.svg" alt="Wish-list Icon" />
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <img src="<?= BASE_URL ?>public/images/text elka stage.png" alt="ELKA STAGE" class="topbar-logo"/>
        </div>
        <div class="topbar-right">
            <div class="language-switch">
                <img src="<?= BASE_URL ?>public/images/imagefr.png" alt="Language" class="flag-icon" id="language-flag" />
                <span id="language-text">Francais</span>
                <button class="lang-toggle" id="lang-toggle">⇃</button>
            </div>
            <?php if ($role): ?>
            <div class="user" id="user-block">
                <div class="user-info">
                    <?php 
                        $profilePic = (isset($_SESSION['user']['profile_picture']) && !empty($_SESSION['user']['profile_picture']))
                                        ? $_SESSION['user']['profile_picture']
                                        : 'public/uploads/profile_pictures/defaultpfpuser.jpg';
                    ?>
                    <img src="<?= BASE_URL . $profilePic; ?>" alt="User" class="avatar"/>
                    <div class="info">
                        <div class="name"><?= htmlspecialchars($userName) ?></div>
                        <div class="role"><?= htmlspecialchars($role) ?></div>
                    </div>
                </div>
                <div class="user-dropdown" id="user-dropdown">
                    <a href="index.php?controller=auth&action=logout">Se déconnecter</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenu principal (zone rouge) -->
    <div class="content-red">
        <div class="content-area content">
            <!-- Contenu de la page -->
