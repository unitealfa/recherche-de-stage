<?php include "app/Views/layout/header.php"; ?>

<h2>Liste des entreprises</h2>
<?php if (isset($_SESSION['user']) && (strtoupper($_SESSION['user']['role']) === 'ADMIN' || strtoupper($_SESSION['user']['role']) === 'PILOTE')): ?>
    <p><a href="index.php?controller=company&action=create" class="btn">Ajouter une entreprise</a></p>
<?php endif; ?>

<form method="get" action="index.php">
    <input type="hidden" name="controller" value="company">
    <input type="hidden" name="action" value="index">
    <label for="search">Rechercher par nom :</label>
    <input type="text" name="search" id="search" placeholder="Entrez le nom de l'entreprise" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Rechercher</button>
</form>

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <th>Photo</th>
    <th>Nom</th>
    <th>Description</th>
    <th>Email contact</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($companies as $company): ?>
  <tr>
    <td>
        <img class="round-pfp" src="<?= BASE_URL ?>public/uploads/companies/<?= htmlspecialchars($company['profile_picture'] ?? 'default_pfp.png'); ?>" alt="Image de <?= htmlspecialchars($company['name']); ?>">
    </td>
    <!-- Suppression de l'affichage de l'ID -->
    <td><?= htmlspecialchars($company['name'] ?? ''); ?></td>
    <td><?= htmlspecialchars($company['description'] ?? 'Non renseigné'); ?></td>
    <td><?= htmlspecialchars($company['email_contact'] ?? 'Non renseigné'); ?></td>
    <td>
      <a href="index.php?controller=company&action=show&id=<?= htmlspecialchars($company['company_id'] ?? ''); ?>">Voir</a>
      <?php if (isset($_SESSION['user']) && (strtoupper($_SESSION['user']['role']) === 'ADMIN' || strtoupper($_SESSION['user']['role']) === 'PILOTE')): ?>
         | <a href="index.php?controller=company&action=edit&id=<?= htmlspecialchars($company['company_id'] ?? ''); ?>">Modifier</a>
         | <a href="index.php?controller=company&action=delete&id=<?= htmlspecialchars($company['company_id'] ?? ''); ?>" onclick="return confirm('Confirmer la suppression');">Supprimer</a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>

<?php include "app/Views/layout/footer.php"; ?>
