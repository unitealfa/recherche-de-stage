<?php include "app/Views/layout/header.php"; ?>
<h2>Modifier l'utilisateur</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=user&action=edit">
  <input type="hidden" name="id" value="<?= $user['user_id']; ?>">
  <label>Prénom :</label>
  <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
  <br>
  <label>Nom :</label>
  <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>
  <br>
  <label>Email :</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
  <br>
  <label>Rôle :</label>
  <select name="role">
    <option value="ETUDIANT" <?= ($user['role']=='ETUDIANT')?'selected':''; ?>>Étudiant</option>
    <option value="PILOTE" <?= ($user['role']=='PILOTE')?'selected':''; ?>>Pilote</option>
    <option value="ADMIN" <?= ($user['role']=='ADMIN')?'selected':''; ?>>Administrateur</option>
  </select>
  <br>
  <button type="submit">Modifier</button>
</form>
<a href="index.php?controller=user&action=index">Retour</a>
<?php include "app/Views/layout/footer.php"; ?>
