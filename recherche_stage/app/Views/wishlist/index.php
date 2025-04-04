<?php include "app/Views/layout/header.php"; ?>

<!-- Lien vers la feuille de style wishlist -->
<link rel="stylesheet" href="public/css/wishlist.css">

<h1>Ma Wish List</h1>

<!-- Barre de recherche (optionnelle). 
     Vous pouvez l'enlever si vous n'en avez pas besoin. -->
<div class="filter-bar">
    <div class="search-bar">
        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.545 15.035l-3.722-3.722A5.476 5.476 0 0014 6.5
                     5.507 5.507 0 008.5 1 5.507 5.507 0 003 6.5
                     5.507 5.507 0 008.5 12a5.476 5.476 0 004.813-2.177l3.722
                     3.722a.5.5 0 00.71 0 .5.5 0 000-.71zM8.5 11A4.505 4.505 0
                     014 6.5 4.505 4.505 0 018.5 2 4.505 4.505 0 0113 6.5
                     4.505 4.505 0 018.5 11z"></path>
        </svg>
        <input type="text" placeholder="Rechercher dans la wishlist" />
    </div>
</div>

<?php if (empty($wishListItems)): ?>
    <p>Votre wish list est vide.</p>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Entreprise</th>
                    <th>Offre</th>
                    <th>Date d'ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($wishListItems as $item): ?>
                <tr>
                    <!-- Photo -->
                    <td>
                        <div class="enterprise-logo">
                            <img 
                              src="<?= BASE_URL ?>public/uploads/companies/<?= htmlspecialchars($item['profile_picture'] ?? 'default_pfp.png'); ?>" 
                              alt="Image de <?= htmlspecialchars($item['company_name'] ?? 'Inconnue'); ?>"
                            >
                        </div>
                    </td>

                    <!-- Entreprise -->
                    <td><?= htmlspecialchars($item['company_name'] ?? 'Inconnue'); ?></td>

                    <!-- Offre (titre) -->
                    <td><?= htmlspecialchars($item['title'] ?? 'Inconnue'); ?></td>

                    <!-- Date d'ajout -->
                    <td><?= htmlspecialchars($item['date_added']); ?></td>

                    <!-- Actions -->
                    <td>
                        <div class="action-pill">
                            <!-- Bouton "Voir" (éventuellement, si vous voulez afficher l'offre) 
                                 Ici, on pourrait rediriger sur un show/id ou autre. 
                                 Mettez le lien/onclick approprié -->
                            <div class="action-part eye"
                                 onclick="window.location.href='index.php?controller=offer&action=show&id=<?= htmlspecialchars($item['offer_id']); ?>'">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="2" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 12C5.366 14.739 8.313 17 
                                             12 17s6.634-2.261 9-5c-2.366-2.466
                                             -5.314-5-9-5S5.366 9.534 3 12z"
                                          stroke-width="2" stroke-linecap="round" 
                                          stroke-linejoin="round"/>
                                </svg>
                                <span class="tooltip">Voir</span>
                            </div>

                            <!-- Bouton "Retirer" de la wishlist -->
                            <div class="action-part apply"
                                 onclick="if(confirm('Confirmer la suppression de l\'offre de la wishlist ?')) {
                                     window.location.href='index.php?controller=wishlist&action=remove&offer_id=<?= htmlspecialchars($item['offer_id']); ?>';
                                 }">
                                <!-- L'icône "poubelle" ou "corbeille" 
                                     ou l'icône "apply" (adaptée du design) -->
                                <svg version="1.1" id="Layer_1"
                                     xmlns="http://www.w3.org/2000/svg" 
                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 32 32" enable-background="new 0 0 32 32" 
                                     xml:space="preserve">
                                    <path fill="none" stroke="#CF63F0" stroke-width="2" 
                                          stroke-miterlimit="10" 
                                          d="M23,27H11c-1.1,0-2-0.9-2-2V8h16v17 
                                             C25,26.1,24.1,27,23,27z"/>
                                    <line fill="none" stroke="#CF63F0" stroke-width="2" 
                                          stroke-miterlimit="10" x1="27" y1="8" 
                                          x2="7" y2="8"/>
                                    <path fill="none" stroke="#CF63F0" stroke-width="2" 
                                          stroke-miterlimit="10"
                                          d="M14,8V6c0-0.6,0.4-1,1-1h4
                                             c0.6,0,1,0.4,1,1v2"/>
                                    <line fill="none" stroke="#CF63F0" stroke-width="2" 
                                          stroke-miterlimit="10" x1="17" y1="23" 
                                          x2="17" y2="12"/>
                                    <line fill="none" stroke="#CF63F0" stroke-width="2" 
                                          stroke-miterlimit="10" x1="21" y1="23" 
                                          x2="21" y2="12"/>
                                    <line fill="none" stroke="#CF63F0" stroke-width="2"
                                          stroke-miterlimit="10" x1="13" y1="23"
                                          x2="13" y2="12"/>
                                </svg>
                                <span class="tooltip">Retirer</span>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>


<?php include "app/Views/layout/footer.php"; ?>
