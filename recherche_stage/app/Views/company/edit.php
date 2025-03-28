<?php include "app/Views/layout/header.php"; ?>
<h2>Modifier l'entreprise</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=company&action=edit" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $company['company_id']; ?>">
  <input type="hidden" name="existing_profile_picture" value="<?= htmlspecialchars($company['profile_picture'] ?? 'default_pfp.png'); ?>">
  
  <label>Nom :</label>
  <input type="text" name="name" value="<?= htmlspecialchars($company['name']); ?>" required>
  <br>
  
  <label>Description :</label>
  <textarea name="description" required><?= htmlspecialchars($company['description']); ?></textarea>
  <br>
  
  <label>Email contact :</label>
  <input type="email" name="email_contact" value="<?= htmlspecialchars($company['email_contact']); ?>" required>
  <br>
  
  <label>Téléphone :</label>
  <input type="text" name="phone_contact" value="<?= htmlspecialchars($company['phone_contact']); ?>">
  <br>
  
  <label>Image de profil :</label>
  <input type="file" name="profile_picture">
  <br>
  
  <button type="submit">Modifier</button>
</form>
<a href="index.php?controller=company&action=index">Retour</a>
<?php include "app/Views/layout/footer.php"; ?>
