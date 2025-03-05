<?php include "app/Views/layout/header.php"; ?>
<h2>Connexion</h2>
<?php

if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=auth&action=login">
  <label>Email :</label>
  <input type="email" name="email" required>
  <br>
  <label>Mot de passe :</label>
  <input type="password" name="password" required>
  <br>
  <button type="submit">Se connecter</button>
</form>
<?php include "app/Views/layout/footer.php"; ?>
