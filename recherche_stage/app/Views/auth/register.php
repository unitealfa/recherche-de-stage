<?php include "app/Views/layout/header.php"; ?>
<h2>Inscription</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=auth&action=register">
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
  <label>Confirmer le mot de passe :</label>
  <input type="password" name="confirm_password" required>
  <br>
  <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit ? <a href="index.php?controller=auth&action=login">Connexion</a></p>
<?php include "app/Views/layout/footer.php"; ?>
