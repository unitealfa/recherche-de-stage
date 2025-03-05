<?php include "app/Views/layout/header.php"; ?>
<h2>Détails de la candidature</h2>
<p><strong>ID de la candidature :</strong> <?= $candidature['candidature_id']; ?></p>
<p><strong>ID de l'offre :</strong> <?= $candidature['offer_id']; ?></p>
<p><strong>Date de candidature :</strong> <?= $candidature['date_candidature']; ?></p>
<p><strong>Lettre de motivation :</strong></p>
<p><?= nl2br(htmlspecialchars($candidature['motivation_letter'])); ?></p>
<?php if (!empty($candidature['cv_path'])): ?>
    <p><strong>CV :</strong> <a href="<?= $candidature['cv_path']; ?>" target="_blank">Voir le CV</a></p>
<?php endif; ?>
<p>
  <a href="index.php?controller=candidature&action=edit&id=<?= $candidature['candidature_id']; ?>">Modifier</a>
  &nbsp;|&nbsp;
  <a href="index.php?controller=candidature&action=delete&id=<?= $candidature['candidature_id']; ?>" onclick="return confirm('Confirmer la suppression');">Supprimer</a>
  &nbsp;|&nbsp;
  <a href="index.php?controller=candidature&action=index">Retour à la liste</a>
</p>
<?php include "app/Views/layout/footer.php"; ?>
