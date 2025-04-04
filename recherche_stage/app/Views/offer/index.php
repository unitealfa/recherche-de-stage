<?php include "app/Views/layout/header.php"; ?>

<!-- Lien vers la feuille de style -->
<link rel="stylesheet" href="public/css/offre.css">

<!-- Titre principal : 
     "Liste des offres de stage" + Icône "plus" à qq pixels à droite (si ADMIN/PILOTE) -->
<h1 style="display: inline-flex; align-items: center;">
    Liste des offres de stage

    <?php
    if (isset($_SESSION['user'])) {
        $role = strtoupper($_SESSION['user']['role']);
        if ($role === 'ADMIN' || $role === 'PILOTE') : ?>
            <!-- Lien "Ajouter offre" avec icône plus en rose, 
                 décalé à 10px sur la droite du texte -->
            <a href="index.php?controller=offer&action=create" style="margin-left: 20px;">
                <svg class="svg-huge-plus" version="1.1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path class="st0" d="M255.996,0C114.617,0,0,114.609,0,256.004
                      C0,397.383,114.617,512,255.996,512C397.391,512,512,397.383,
                      512,256.004C512,114.609,397.391,0,255.996,0z M352.178,
                      202.834l-53.162,53.17l96.182,96.174L255.996,491.389l-43.02-43.012
                      l96.19-96.199l-53.17-53.162l-96.182,96.182L20.611,256.004L63.631,213
                      l96.182,96.174l53.162-53.17l-96.174-96.191L255.996,20.619l43.02,43.004
                      l-96.182,96.191L255.996,213l96.182-96.198l139.211,139.202l-43.02,43.012
                      L352.178,202.834z"/>
                </svg>
            </a>
    <?php endif; } ?>
</h1>

<!-- Barre de filtre (formulaire GET : 3 champs + bouton Rechercher + bouton Reset) -->
<div class="filter-bar">
    <form method="get" action="index.php" class="search-bar multi-fields">
        <input type="hidden" name="controller" value="offer">
        <input type="hidden" name="action" value="index">

        <!-- Champ Titre -->
        <input 
          type="text" 
          name="title" 
          placeholder="Titre" 
          value="<?= htmlspecialchars($_GET['title'] ?? '') ?>"
        />

        <!-- Champ Entreprise -->
        <input 
          type="text" 
          name="company" 
          placeholder="Entreprise" 
          value="<?= htmlspecialchars($_GET['company'] ?? '') ?>"
        />

        <!-- Champ Compétences -->
        <input 
          type="text" 
          name="competencies" 
          placeholder="Compétences" 
          value="<?= htmlspecialchars($_GET['competencies'] ?? '') ?>"
        />

        <!-- Bouton Rechercher (icône loupe) -->
        <button type="submit" class="search-btn">
            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.545 15.035l-3.722-3.722A5.476 5.476 0 0014 
                         6.5 5.507 5.507 0 008.5 1 5.507 5.507 0 003 6.5 
                         5.507 5.507 0 008.5 12a5.476 5.476 0 004.813-2.177
                         l3.722 3.722a.5.5 0 00.71 0 .5.5 0 000-.71zM8.5 
                         11A4.505 4.505 0 014 6.5 4.505 4.505 0 018.5 2 
                         4.505 4.505 0 0113 6.5 4.505 4.505 0 018.5 11z"/>
            </svg>
            Rechercher
        </button>

        <!-- Bouton/lien "Reset Filter" (icône rose) -->
        <a href="index.php?controller=offer&action=index" class="search-btn reset-btn">
            <svg viewBox="0 0 24 24" fill="#FF43DD" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 20.75C10.078 20.7474 8.23546 19.9827 6.8764 
                         18.6236C5.51733 17.2645 4.75265 15.422 4.75 13.5C4.75 
                         13.3011 4.82902 13.1103 4.96967 12.9697C5.11032 12.829 
                         5.30109 12.75 5.5 12.75C5.69891 12.75 5.88968 12.829 
                         6.03033 12.9697C6.17098 13.1103 6.25 13.3011 6.25 
                         13.5C6.25 14.6372 6.58723 15.7489 7.21905 16.6945C7.85087 
                         17.6401 8.74889 18.3771 9.79957 18.8123C10.8502 19.2475 
                         12.0064 19.3614 13.1218 19.1395C14.2372 18.9177 15.2617 
                         18.37 16.0659 17.5659C16.87 16.7617 17.4177 15.7372 
                         17.6395 14.6218C17.8614 13.5064 17.7475 12.3502 17.3123 
                         11.2996C16.8771 10.2489 16.1401 9.35087 15.1945 8.71905
                         C14.2489 8.08723 13.1372 7.75 12 7.75H9.5C9.30109 7.75 
                         9.11032 7.67098 8.96967 7.53033C8.82902 7.38968 8.75 
                         7.19891 8.75 7C8.75 6.80109 8.82902 6.61032 8.96967 
                         6.46967C9.11032 6.32902 9.30109 6.25 9.5 6.25H12C13.9228 
                         6.25 15.7669 7.01384 17.1265 8.37348C18.4862 9.73311 
                         19.25 11.5772 19.25 13.5C19.25 15.4228 18.4862 17.2669 
                         17.1265 18.6265C15.7669 19.9862 13.9228 20.75 12 
                         20.75Z"/>
                <path d="M12 10.75C11.9015 10.7505 11.8038 10.7313 11.7128 
                         10.6935C11.6218 10.6557 11.5392 10.6001 11.47 
                         10.53L8.47 7.53003C8.32955 7.38941 8.25066 7.19878 
                         8.25066 7.00003C8.25066 6.80128 8.32955 6.61066 
                         8.47 6.47003L11.47 3.47003C11.5387 3.39634 11.6215 
                         3.33724 11.7135 3.29625C11.8055 3.25526 11.9048 
                         3.23322 12.0055 3.23144C12.1062 3.22966 12.2062 
                         3.24819 12.2996 3.28591C12.393 3.32363 12.4778 
                         3.37977 12.549 3.45099C12.6203 3.52221 12.6764 
                         3.60705 12.7141 3.70043C12.7518 3.79382 12.7704 
                         3.89385 12.7686 3.99455C12.7668 4.09526 12.7448 
                         4.19457 12.7038 4.28657C12.6628 4.37857 12.6037 
                         4.46137 12.53 4.53003L10.06 7.00003L12.53 9.47003
                         C12.6704 9.61066 12.7493 9.80128 12.7493 10C12.7493 
                         10.1988 12.6704 10.3894 12.53 10.53C12.4608 10.6001 
                         12.3782 10.6557 12.2872 10.6935C12.1962 10.7313 
                         12.0985 10.7505 12 10.75Z"/>
            </svg>
            Reset
        </a>
    </form>
</div>

<!-- Tableau stylé des offres -->
<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>Titre</th>
            <th>Entreprise</th>
            <th>Compétences</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Date du jour pour checker expiration
        $today = date('Y-m-d');

        if (!empty($offers)):
            foreach ($offers as $offer):
                // Indicateur offre expirée ?
                $endDate = $offer['end_date'] ?? '';
                if (!empty($endDate) && $today > $endDate) {
                    $expiredIndicator = '<span style="color: red;" title="Offre expirée">✖</span>';
                } else {
                    $expiredIndicator = '';
                }
        ?>
            <tr>
                <!-- Colonne Titre + Logo -->
                <td>
                    <div class="title-cell">
                        <div class="enterprise-logo">
                            <img
                                src="<?= BASE_URL ?>public/uploads/companies/<?= htmlspecialchars($offer['profile_picture'] ?? 'default_pfp.png'); ?>"
                                alt="Logo de <?= htmlspecialchars($offer['company_name']); ?>"
                            >
                        </div>
                        <span>
                            <?= htmlspecialchars($offer['title'] ?? ''); ?>
                            <?= $expiredIndicator; ?>
                        </span>
                    </div>
                </td>

                <!-- Entreprise -->
                <td><?= htmlspecialchars($offer['company_name'] ?? 'Entreprise inconnue'); ?></td>

                <!-- Compétences -->
                <td><?= htmlspecialchars($offer['competencies'] ?? 'Non renseigné'); ?></td>

                <!-- Description -->
                <td><?= htmlspecialchars($offer['description'] ?? 'Non renseigné'); ?></td>

                <!-- Action (icônes) -->
                <td>
                    <div class="action-pill">
                        <!-- Voir (toujours) -->
                        <div class="action-part eye"
                             onclick="window.location.href='index.php?controller=offer&action=show&id=<?= htmlspecialchars($offer['offer_id'] ?? ''); ?>'">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 12C5.366 14.739 8.313 17 12 17s6.634-2.261 
                                         9-5c-2.366-2.466-5.314-5-9-5S5.366 9.534 3 
                                         12z" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                            <span class="tooltip">Voir</span>
                        </div>

                        <?php
                        // Rôle
                        $role = isset($_SESSION['user']) ? strtoupper($_SESSION['user']['role']) : '';

                        // ADMIN ou ETUDIANT : Postuler + Wishlist
                        if ($role === 'ADMIN' || $role === 'ETUDIANT'): ?>
                            <div class="action-part edit"
                                 onclick="window.location.href='index.php?controller=candidature&action=postuler&offer_id=<?= htmlspecialchars($offer['offer_id'] ?? '') ?>'">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.4142 2.586C18.7892 2.21103 19.3676 2.21103
                                             19.7426 2.586L21.4142 4.2576C21.7892 4.6326 
                                             21.7892 5.21103 21.4142 5.58603L10 17
                                             L6 18L7 14L18.4142 2.586z" />
                                </svg>
                                <span class="tooltip">Postuler</span>
                            </div>

                            <div class="action-part heart"
                                 onclick="toggleHeart(this); window.location.href='index.php?controller=wishlist&action=add&offer_id=<?= htmlspecialchars($offer['offer_id'] ?? '') ?>'">
                                <div class="heart-unchecked">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M12 4.528a6 6 0 0 0-8.243 8.715l6.829 6.828
                                                 a2 2 0 0 0 2.828 0l6.829-6.828A6 6 
                                                 0 0 0 12 4.528z" />
                                    </svg>
                                </div>
                                <div class="heart-checked">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 4.528a6 6 0 0 0-8.243 8.715l6.829
                                                 6.828a2 2 0 0 0 2.828 0l6.829-6.828
                                                 A6 6 0 0 0 12 4.528z"/>
                                    </svg>
                                </div>
                                <span class="tooltip">Wishlist</span>
                            </div>
                        <?php endif; ?>

                        <?php
                        // PILOTE : Modifier + Supprimer
                        if ($role === 'PILOTE'): ?>
                            <div class="action-part edit"
                                 onclick="window.location.href='index.php?controller=offer&action=edit&id=<?= htmlspecialchars($offer['offer_id'] ?? '') ?>'">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.4142 2.586C18.7892 2.21103 19.3676 
                                             2.21103 19.7426 2.586L21.4142 4.2576
                                             C21.7892 4.6326 21.7892 5.21103 
                                             21.4142 5.58603L10 17L6 18L7 14
                                             L18.4142 2.586z" />
                                </svg>
                                <span class="tooltip">Modifier</span>
                            </div>

                            <div class="action-part edit"
                                 onclick="if(confirm('Confirmer la suppression ?')) {
                                     window.location.href='index.php?controller=offer&action=delete&id=<?= htmlspecialchars($offer['offer_id'] ?? '') ?>';
                                 }">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 3v1H4v2h16V4h-5V3c0-.55-.45-1-1-1
                                             h-4c-.55 0-1 .45-1 1zm2 4v10c0 .55.45
                                             1 1 1s1-.45 1-1V7h2v10c0 1.65-1.35
                                             3-3 3s-3-1.35-3-3V7h2z"/>
                                </svg>
                                <span class="tooltip">Supprimer</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="5">Aucune offre trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="index.php?controller=offer&action=index&page=<?= $page - 1 ?>
           &title=<?= urlencode($title) ?>
           &company=<?= urlencode($company) ?>
           &competencies=<?= urlencode($competencies) ?>">
            Précédent
        </a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i == $page): ?>
            <strong><?= $i ?></strong>
        <?php else: ?>
            <a href="index.php?controller=offer&action=index&page=<?= $i ?>
               &title=<?= urlencode($title) ?>
               &company=<?= urlencode($company) ?>
               &competencies=<?= urlencode($competencies) ?>">
                <?= $i ?>
            </a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="index.php?controller=offer&action=index&page=<?= $page + 1 ?>
           &title=<?= urlencode($title) ?>
           &company=<?= urlencode($company) ?>
           &competencies=<?= urlencode($competencies) ?>">
            Suivant
        </a>
    <?php endif; ?>
</div>

<!-- Script pour wishlist -->
<script>
function toggleHeart(el) {
    el.classList.toggle('checked');
}
</script>

<?php include "app/Views/layout/footer.php"; ?>
