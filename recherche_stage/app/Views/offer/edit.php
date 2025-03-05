<?php include "app/Views/layout/header.php"; ?>

<h2>Modifier l'offre</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<!-- On conserve l'ID de l'offre dans un champ caché -->
<form method="post" action="index.php?controller=offer&action=edit">
  <input type="hidden" name="id" value="<?= htmlspecialchars($offer['offer_id']); ?>">
  <!-- Ajoutez ici le champ caché pour company_id -->
  <input type="hidden" name="company_id" value="<?= htmlspecialchars($offer['company_id']); ?>">
  
  <!-- Champ Titre avec un placeholder pour guider l'utilisateur -->
  <label>Titre :</label>
  <input type="text" name="title" placeholder="Stage de quoi ?" value="<?= htmlspecialchars($offer['title']); ?>" required>
  <br>
  
  <!-- Champ Description -->
  <label>Description :</label>
  <textarea name="description" required><?= htmlspecialchars($offer['description']); ?></textarea>
  <br>
  
  <!-- Champ Compétences -->
  <label>Compétences :</label>
  <input type="text" name="competencies" value="<?= htmlspecialchars($offer['competencies']); ?>">
  <br>
  
  <!-- Champ Rémunération -->
  <label>Rémunération :</label>
  <input type="number" step="0.01" name="remuneration_base" value="<?= htmlspecialchars($offer['remuneration_base']); ?>">
  <br>
  
  <!-- Champ Date début -->
  <label>Date début :</label>
  <input type="date" name="start_date" value="<?= htmlspecialchars($offer['start_date']); ?>" required>
  <br>
  
  <!-- Champ Date fin -->
  <label>Date fin :</label>
  <input type="date" name="end_date" value="<?= htmlspecialchars($offer['end_date']); ?>" required>
  <br>
  
  <button type="submit">Modifier</button>
</form>

<a href="index.php?controller=offer&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
