<?php include "app/Views/layout/header.php"; ?>

<!-- On ajoute la feuille de style dédiée aux utilisateurs -->
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/user.css">

<h1>Liste des utilisateurs</h1>

<?php
$role = isset($_SESSION['user']['role']) ? strtoupper($_SESSION['user']['role']) : '';

if ($role === 'ADMIN') {
    echo "<h2></h2>";
} elseif ($role === 'PILOTE') {
    echo "<h2>Gérer les comptes étudiants</h2>";
} else {
    echo "<h2>Mes utilisateurs ?</h2>";
}
?>

<div class="filter-bar">
    <!-- Barre de recherche (uniquement ADMIN/PILOTE) -->
    <?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
      <form method="get" action="index.php" class="search-bar">
        <input type="hidden" name="controller" value="user">
        <input type="hidden" name="action" value="index">
        <!-- Icône de loupe -->
        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path d="M17.545 15.035l-3.722-3.722A5.476 
                   5.476 0 0014 6.5 5.507 5.507 0 
                   008.5 1 5.507 5.507 0 003 6.5 
                   5.507 5.507 0 008.5 12a5.476 
                   5.476 0 004.813-2.177l3.722 
                   3.722a.5.5 0 00.71 0 .5.5 
                   0 000-.71zM8.5 11A4.505 4.505 
                   0 014 6.5 4.505 4.505 0 018.5 
                   2 4.505 4.505 0 0113 6.5 
                   4.505 4.505 0 018.5 11z" />
        </svg>
        <input 
          type="text" 
          name="search" 
          id="search"
          placeholder="Nom, email..."
          value="<?= isset($search) ? htmlspecialchars($search) : '' ?>"
        />
        <!-- Le bouton est invisible, Enter suffit -->
        <button type="submit" style="display:none;"></button>
      </form>
    <?php endif; ?>

    <!-- Bouton créer un compte (si Admin/Pilote) -->
    <?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
    <div class="add-button-container">
      <a href="index.php?controller=user&action=create" title="Créer un nouveau compte">
        <svg
          class="svg-huge-plus"
          version="1.1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 512 512"
        >
          <path d="
            M256,0C114.6,0,0,114.6,0,256s114.6,256,
            256,256s256-114.6,256-256S397.4,0,
            256,0z M405.3,277.3c0,11.8-9.5,
            21.3-21.3,21.3h-85.3V384c0,
            11.8-9.5,21.3-21.3,21.3h-42.7
            c-11.8,0-21.3-9.6-21.3-21.3v-85.3H128
            c-11.8,0-21.3-9.6-21.3-21.3v-42.7
            c0-11.8,9.5-21.3,21.3-21.3h85.3V128
            c0-11.8,9.5-21.3,21.3-21.3h42.7
            c11.8,0,21.3,9.6,21.3,21.3v85.3H384
            c11.8,0,21.3,9.6,21.3,21.3V277.3z
          " />
        </svg>
      </a>
    </div>
    <?php endif; ?>
</div>

<!-- Tableau stylé -->
<div class="table-container">
  <table>
    <thead>
      <tr>
        <th>Photo de profil</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)): ?>
      <?php foreach ($users as $user): ?>
        <tr>
          <!-- Photo de profil -->
          <td>
            <div class="avatar-cell">
              <div class="user-avatar">
                <?php if (!empty($user['profile_picture'])): ?>
                  <img 
                    src="<?= BASE_URL . htmlspecialchars($user['profile_picture']); ?>" 
                    alt="pfp"
                  />
                <?php else: ?>
                  <img 
                    src="<?= BASE_URL; ?>public/uploads/profile_pictures/defaultpfpuser.jpg" 
                    alt="pfp"
                  />
                <?php endif; ?>
              </div>
            </div>
          </td>

          <!-- Nom -->
          <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>

          <!-- Email -->
          <td><?= htmlspecialchars($user['email']); ?></td>

          <!-- Rôle -->
          <td><?= htmlspecialchars($user['role']); ?></td>

          <!-- Actions -->
          <td>
            <div class="action-pill">
              <!-- Voir -->
              <div 
                class="action-part see" 
                onclick="window.location.href='index.php?controller=user&action=show&id=<?= htmlspecialchars($user['user_id']); ?>';"
              >
                <svg viewBox="0 0 32 32">
                  <circle cx="16" cy="16" r="2" />
                  <path d="
                    M16,8C9.373,8,4,14,4,16c0,3,
                    5.373,8,12,8s12-6,12-8S22.627,
                    8,16,8z M16,20.5c-2.485,0-4.5
                    -2.015-4.5-4.5s2.015-4.5,4.5
                    -4.5s4.5,2.015,4.5,4.5
                    S18.485,20.5,16,20.5z
                  " />
                </svg>
                <span class="tooltip">Voir</span>
              </div>

              <!-- Modifier -->
              <?php if ($role === 'ADMIN' || $role === 'PILOTE'): ?>
              <div 
                class="action-part edit" 
                onclick="window.location.href='index.php?controller=user&action=edit&id=<?= htmlspecialchars($user['user_id']); ?>';"
              >
                <svg fill="currentColor" viewBox="0 0 24 24">
                  <path d="
                    M22,7.24a1,1,0,0,0-.29-.71
                    L17.47,2.29A1,1,0,0,0,16.76,
                    2a1,1,0,0,0-.71.29L13.22,5.12
                    h0L2.29,16.05a1,1,0,0,0-.29.71V21
                    a1,1,0,0,0,1,1H7.24A1,1,0,0,0,
                    8,21.71L18.87,10.78h0L21.71,8a1.19,
                    1.19,0,0,0,.22-.33,1,1,0,0,0,0
                    -.24.7.7,0,0,0,0-.14Z
                    M6.83,20H4V17.17l9.93-9.93,2.83,2.83Z
                    M18.17,8.66,15.34,5.83l1.42-1.41,
                    2.82,2.82Z
                  " />
                </svg>
                <span class="tooltip">Modifier</span>
              </div>

              <!-- Supprimer -->
              <div 
                class="action-part delete"
                onclick="if(confirm('Confirmer la suppression ?')) window.location.href='index.php?controller=user&action=delete&id=<?= htmlspecialchars($user['user_id']); ?>';"
              >
                <svg 
                  fill="currentColor"
                  viewBox="-3 -2 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                  preserveAspectRatio="xMinYMin"
                  class="jam jam-trash"
                >
                  <path d="
                    M6 2V1a1 1 0 0 1 1
                    -1h4a1 1 0 0 1 1
                    1v1h4a2 2 0 0 1 2
                    2v1a2 2 0 0 1-2
                    2h-.133l-.68 10.2a3
                    3 0 0 1-2.993
                    2.8H5.826a3 3 0 0
                    1-2.993-2.796L2.137
                    7H2a2 2 0 0 1-2-2V4a2 2
                    0 0 1 2-2h4zm10
                    2H2v1h14V4zM4.141
                    7l.687 10.068a1
                    1 0 0 0 .998.932h6.368a1
                    1 0 0 0 .998-.934
                    L13.862 7h-9.72zM7
                    8a1 1 0 0 1 1,1v7a1 1 0 0
                    1-2,0V9A1 1 0 0 1,
                    7,8zm4 0a1 1 0 0 1 1
                    1v7a1 1 0 0 1-2,
                    0V9A1 1 0 0 1,11,8z
                  " />
                </svg>
                <span class="tooltip">Supprimer</span>
              </div>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="5">Aucun utilisateur trouvé.</td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="index.php?controller=user&action=index&page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $page): ?>
      <strong><?= $i ?></strong>
    <?php else: ?>
      <a href="index.php?controller=user&action=index&page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="index.php?controller=user&action=index&page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
  <?php endif; ?>
</div>

<!-- Si tu veux rajouter du JS -->
<script src="<?= BASE_URL ?>public/js/user.js"></script>
<script>
    /* EXEMPLE pour wishlist s'il y avait un coeur, etc. */
    function toggleHeart(el) {
      el.classList.toggle('checked');
    }
    // Ajoute tes fonctions de suppression, de modification, etc.
  </script>

<?php include "app/Views/layout/footer.php"; ?>
