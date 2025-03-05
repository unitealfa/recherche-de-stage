<?php include "app/Views/layout/header.php"; ?>

<h2>Détails de l'offre</h2>

<!-- Affichage des informations de l'offre -->
<p><strong>Titre :</strong> <?= htmlspecialchars($offer['title']); ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($offer['description'] ?? 'Non renseigné'); ?></p>
<p><strong>Compétences :</strong> <?= htmlspecialchars($offer['competencies'] ?? 'Non renseigné'); ?></p>
<p><strong>Rémunération :</strong> <?= htmlspecialchars($offer['remuneration_base'] ?? ''); ?></p>
<p><strong>Date début :</strong> <?= htmlspecialchars($offer['start_date'] ?? ''); ?></p>
<p><strong>Date fin :</strong> <?= htmlspecialchars($offer['end_date'] ?? ''); ?></p>

<!-- Au lieu d'afficher directement le nom de l'entreprise, on affiche un lien -->
<p>
  <strong>Entreprise :</strong>
  <a href="index.php?controller=company&action=show&id=<?= htmlspecialchars($offer['company_id']); ?>">
    Consulter l'entreprise
  </a>
</p>

<!-- Liens complémentaires pour les étudiants -->
<?php if (isset($_SESSION['user']) && strtoupper($_SESSION['user']['role']) === 'ETUDIANT'): ?>
    <p>
      <a href="index.php?controller=candidature&action=postuler&offer_id=<?= htmlspecialchars($offer['offer_id']); ?>">Postuler</a>
      | <a href="index.php?controller=wishlist&action=add&offer_id=<?= htmlspecialchars($offer['offer_id']); ?>">Ajouter à ma wish list</a>
    </p>
<?php endif; ?>

<a href="index.php?controller=offer&action=index">Retour à la liste des offres</a>

<?php include "app/Views/layout/footer.php"; ?>
