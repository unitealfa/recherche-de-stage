<?php include "app/Views/layout/header.php"; ?>

<h2>Détails de l'utilisateur</h2>
<!-- La ligne affichant l'ID a été supprimée -->
<p>Nom : <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
<p>Email : <?= htmlspecialchars($user['email']); ?></p>
<p>Rôle : <?= htmlspecialchars($user['role']); ?></p>
<!-- Les liens utilisent toujours l'ID en interne pour l'édition -->
<a href="index.php?controller=user&action=edit&id=<?= $user['user_id']; ?>">Modifier</a>
<a href="index.php?controller=user&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
