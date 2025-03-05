<?php include "app/Views/layout/header.php"; ?>

<h2>Créer une entreprise</h2>
<?php if (isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=company&action=create">
  <label>Nom :</label>
  <input type="text" name="name" required>
  <br>
  
  <label>Description :</label>
  <textarea name="description" required></textarea>
  <br>
  
  <label>Email contact :</label>
  <input type="email" name="email_contact">
  <br>
  
  <label>Téléphone :</label>
  <input type="text" name="phone_contact">
  <br>
  
  <button type="submit">Créer</button>
</form>

<a href="index.php?controller=company&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
