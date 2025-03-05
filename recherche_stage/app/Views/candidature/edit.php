<?php include "app/Views/layout/header.php"; ?>
<h2>Modifier la candidature</h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>
<form method="post" action="index.php?controller=candidature&action=edit&id=<?= $candidature['candidature_id']; ?>">
  <label>Lettre de motivation :</label>
  <textarea name="motivation_letter" required><?= htmlspecialchars($candidature['motivation_letter']); ?></textarea>
  <br>
  <button type="submit">Modifier</button>
</form>
<a href="index.php?controller=candidature&action=index">Retour</a>
<?php include "app/Views/layout/footer.php"; ?>
