<?php include "app/Views/layout/header.php"; ?>

<?php
$role = isset($_SESSION['user']['role']) ? strtoupper($_SESSION['user']['role']) : '';

if ($role === 'ADMIN') {
    echo "<h2>Gérer tous les utilisateurs</h2>";
    echo '<p><a href="index.php?controller=user&action=create">Créer un compte utilisateur</a></p>';
} elseif ($role === 'PILOTE') {
    echo "<h2>Gérer les comptes étudiants</h2>";
    echo '<p><a href="index.php?controller=user&action=create">Créer un compte étudiant</a></p>';
} else {
    echo "<h2>Mes utilisateurs ?</h2>";
}
?>

<!-- Barre de recherche pour ADMIN et PILOTE -->
<?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
<form method="get" action="index.php">
    <input type="hidden" name="controller" value="user">
    <input type="hidden" name="action" value="index">

    <label for="search">Rechercher :</label>
    <input type="text" name="search" id="search" placeholder="Nom, email...">
    <button type="submit">Rechercher</button>
</form>
<?php endif; ?>

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <!-- Colonne ID supprimée -->
    <th>Nom</th>
    <th>Email</th>
    <th>Rôle</th>
    <th>Actions</th>
  </tr>
  <?php foreach($users as $user): ?>
    <tr>
      <!-- La cellule affichant l'ID a été retirée -->
      <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
      <td><?= htmlspecialchars($user['email']); ?></td>
      <td><?= htmlspecialchars($user['role']); ?></td>
      <td>
        <a href="index.php?controller=user&action=edit&id=<?= $user['user_id']; ?>">Modifier</a> |
        <a href="index.php?controller=user&action=delete&id=<?= $user['user_id']; ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include "app/Views/layout/footer.php"; ?>
