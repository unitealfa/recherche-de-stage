<?php include "app/Views/layout/header.php"; ?>

<h2>Inscription</h2>

<?php if (isset($error)): ?>
  <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="index.php?controller=auth&action=register" enctype="multipart/form-data">
  <label for="first_name">Prénom :</label>
  <input type="text" name="first_name" id="first_name" required>
  <br><br>
  
  <label for="last_name">Nom :</label>
  <input type="text" name="last_name" id="last_name" required>
  <br><br>
  
  <label for="email">Email :</label>
  <input type="email" name="email" id="email" required>
  <br><br>
  
  <label for="password">Mot de passe :</label>
  <input type="password" name="password" id="password" required>
  <br><br>
  
  <label for="confirm_password">Confirmer le mot de passe :</label>
  <input type="password" name="confirm_password" id="confirm_password" required>
  <br><br>
  
  <label for="profile_picture">Photo de profil :</label>
  <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
  <br><br>
  
  <button type="submit">S'inscrire</button>
</form>

<?php include "app/Views/layout/footer.php"; ?>
