<?php include "app/Views/layout/header.php"; ?>

<h2>Liste des offres de stage</h2>

<!-- Formulaire de recherche multi-critères -->
<form method="get" action="index.php">
    <input type="hidden" name="controller" value="offer">
    <input type="hidden" name="action" value="index">

    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" placeholder="Titre de l'offre" value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">
    
    <label for="company">Entreprise :</label>
    <input type="text" name="company" id="company" placeholder="Nom de l'entreprise" value="<?= isset($_GET['company']) ? htmlspecialchars($_GET['company']) : ''; ?>">
    
    <label for="competencies">Compétences :</label>
    <input type="text" name="competencies" id="competencies" placeholder="Compétences requises" value="<?= isset($_GET['competencies']) ? htmlspecialchars($_GET['competencies']) : ''; ?>">
    
    <button type="submit">Rechercher</button>
</form>

<!-- Bouton "Ajouter une offre" pour ADMIN et PILOTE -->
<?php 
if (isset($_SESSION['user'])) {
    $role = strtoupper($_SESSION['user']['role']);
    if ($role === 'ADMIN' || $role === 'PILOTE') {
        echo '<p><a href="index.php?controller=offer&action=create" class="btn">Ajouter une offre</a></p>';
    }
}
?>

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <!-- La colonne ID a été supprimée -->
    <th>Titre</th>
    <th>Entreprise</th>
    <th>Description</th>
    <th>Compétences</th>
    <th>Date fin</th>
    <th>Actions</th>
  </tr>
  <?php 
  // Définir la date du jour pour comparaison
  $today = date('Y-m-d');
  foreach ($offers as $offer): 
      // Vérifier si l'offre est expirée (si la date fin existe et est inférieure à aujourd'hui)
      $endDate = $offer['end_date'] ?? '';
      if (!empty($endDate) && $today > $endDate) {
          $expiredIndicator = '<span style="color: red;" title="Offre expirée">✖</span>';
      } else {
          $expiredIndicator = '';
      }
  ?>
  <tr>
    <td><?= htmlspecialchars($offer['title'] ?? ''); ?></td>
    <td><?= htmlspecialchars($offer['company_name'] ?? 'Entreprise inconnue'); ?></td>
    <td><?= htmlspecialchars($offer['description'] ?? 'Non renseigné'); ?></td>
    <td><?= htmlspecialchars($offer['competencies'] ?? 'Non renseigné'); ?></td>
    <!-- Affichage de la date fin avec l'indicateur si expirée -->
    <td>
      <?= htmlspecialchars($offer['end_date'] ?? ''); ?>
      <?= $expiredIndicator; ?>
    </td>
    <td>
      <a href="index.php?controller=offer&action=show&id=<?= htmlspecialchars($offer['offer_id'] ?? ''); ?>">Voir</a>
      <?php 
        if (isset($_SESSION['user'])) {
            $role = strtoupper($_SESSION['user']['role']);
            if ($role === 'ADMIN' || $role === 'ETUDIANT') {
                echo ' | <a href="index.php?controller=candidature&action=postuler&offer_id=' . htmlspecialchars($offer['offer_id'] ?? '') . '">Postuler</a>';
                echo ' | <a href="index.php?controller=wishlist&action=add&offer_id=' . htmlspecialchars($offer['offer_id'] ?? '') . '">Ajouter à ma wishlist</a>';
            } elseif ($role === 'PILOTE') {
                echo ' | <a href="index.php?controller=offer&action=edit&id=' . htmlspecialchars($offer['offer_id'] ?? '') . '">Modifier</a>';
                echo ' | <a href="index.php?controller=offer&action=delete&id=' . htmlspecialchars($offer['offer_id'] ?? '') . '" onclick="return confirm(\'Confirmer la suppression\');">Supprimer</a>';
            }
        }
      ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>

<?php include "app/Views/layout/footer.php"; ?>
