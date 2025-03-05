<?php include "app/Views/layout/header.php"; ?>

<h2>Ma Wish List</h2>

<?php if (empty($wishListItems)): ?>
    <p>Votre wish list est vide.</p>
<?php else: ?>
    <table border="1" cellspacing="0" cellpadding="5">
       <tr>
           <th>Entreprise</th>
           <th>Offre</th>
           <th>Date d'ajout</th>
           <th>Actions</th>
       </tr>
       <?php foreach ($wishListItems as $item): ?>
         <tr>
            <td><?= htmlspecialchars($item['company_name'] ?? 'Inconnue'); ?></td>
            <td><?= htmlspecialchars($item['title'] ?? 'Inconnue'); ?></td>
            <td><?= htmlspecialchars($item['date_added']); ?></td>
            <td>
                <a href="index.php?controller=wishlist&action=remove&offer_id=<?= htmlspecialchars($item['offer_id']); ?>" onclick="return confirm('Confirmer la suppression de l\'offre de la wish list');">Retirer</a>
            </td>
         </tr>
       <?php endforeach; ?>
    </table>
<?php endif; ?>

<a href="index.php?controller=offer&action=index">Retour à la liste des offres</a>

<?php include "app/Views/layout/footer.php"; ?>
