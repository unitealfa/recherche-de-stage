<?php include "app/Views/layout/header.php"; ?>

<h2>Créer un compte utilisateur</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<form method="post" action="index.php?controller=user&action=create">
  <label>Prénom :</label>
  <input type="text" name="first_name" required>
  <br>
  <label>Nom :</label>
  <input type="text" name="last_name" required>
  <br>
  <label>Email :</label>
  <input type="email" name="email" required>
  <br>
  <label>Mot de passe :</label>
  <input type="password" name="password" required>
  <br>
  
  <?php if (strtoupper($_SESSION['user']['role']) === 'ADMIN'): ?>
    <label>Rôle :</label>
    <select name="role">
      <option value="ETUDIANT">Étudiant</option>
      <option value="PILOTE">Pilote</option>
      <option value="ADMIN">Admin</option>
    </select>
    <br>
  <?php else: ?>
    <!-- Si c'est un pilote, on ne propose pas le choix du rôle, on force ETUDIANT côté contrôleur. -->
  <?php endif; ?>
  
  <button type="submit">Créer</button>
</form>

<a href="index.php?controller=user&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
