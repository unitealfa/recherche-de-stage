<?php include "app/Views/layout/header.php"; ?>

<h2>Dashboard Étudiant</h2>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['first_name']); ?>.</p>
<ul>
    <li><a href="index.php?controller=offer&action=index">Rechercher des offres de stage</a></li>
    <li><a href="index.php?controller=candidature&action=index">Voir mes candidatures</a></li>
    <li><a href="index.php?controller=wishlist&action=index">Voir ma wish list</a></li>
</ul>

<?php include "app/Views/layout/footer.php"; ?>
