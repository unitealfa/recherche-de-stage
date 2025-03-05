<?php include "app/Views/layout/header.php"; ?>

<h2>Dashboard Pilote</h2>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['first_name']); ?> (Pilote).</p>

<ul>
    <li><a href="index.php?controller=company&action=index">Gérer les entreprises</a></li>
    <li><a href="index.php?controller=offer&action=index">Gérer les offres de stage</a></li>
    <li><a href="index.php?controller=user&action=index">Gérer les comptes étudiants</a></li>
    <li><a href="index.php?controller=candidature&action=index">Gérer les candidatures</a></li>
</ul>

<?php include "app/Views/layout/footer.php"; ?>
