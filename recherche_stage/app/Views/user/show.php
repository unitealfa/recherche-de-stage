<?php include "app/Views/layout/header.php"; ?>

<h2>Détails de l'utilisateur</h2>

<?php if (!empty($user['profile_picture'])): ?>
  <p>
    <img src="<?= BASE_URL . $user['profile_picture']; ?>" alt="Photo de profil" style="max-width:150px;">
  </p>
<?php else: ?>
  <p>
    <img src="<?= BASE_URL; ?>public/uploads/profile_pictures/defaultpfpuser.jpg" alt="Photo de profil" style="max-width:150px;">
  </p>
<?php endif; ?>

<p>Nom : <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
<p>Email : <?= htmlspecialchars($user['email']); ?></p>
<p>Rôle : <?= htmlspecialchars($user['role']); ?></p>

<a href="index.php?controller=user&action=edit&id=<?= $user['user_id']; ?>">Modifier</a>
<a href="index.php?controller=user&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
