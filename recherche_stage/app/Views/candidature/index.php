<?php include "app/Views/layout/header.php"; ?>

<!-- Lien vers la feuille de style Candidatures -->
<link rel="stylesheet" href="public/css/Candidatures.css">

<h1>Candidatures</h1>

<!-- Affichage des éventuelles erreurs -->
<?php if (isset($error)): ?>
    <p class="error-message"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php 
// Récupérer le rôle de l'utilisateur depuis la session
$userRole = strtoupper($_SESSION['user']['role'] ?? '');
?>

<!-- Bouton d'ajout de candidature (ETUDIANT) ou d'offre (PILOTE) -->
<div class="add-button-container">
    <?php if ($userRole === 'ETUDIANT'): ?>
        <a href="index.php?controller=candidature&action=postuler" class="btn-add">
            Ajouter une candidature
        </a>
    <?php elseif ($userRole === 'PILOTE'): ?>
        <a href="index.php?controller=offer&action=create" class="btn-add">
            Ajouter une offre
        </a>
    <?php endif; ?>
</div>

<!-- Tableau stylé -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Offre</th>
                <?php if ($userRole === 'ADMIN' || $userRole === 'PILOTE'): ?>
                    <th>Nom</th>
                <?php endif; ?>
                <th>Lettre de motivation</th>
                <th>CV</th>
                <th>Entreprise</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($candidatures as $candidature): ?>
            <tr>
                <!-- Offre -->
                <td><?= htmlspecialchars($candidature['offer_title'] ?? 'Non renseigné'); ?></td>

                <!-- Nom (seulement si ADMIN/PILOTE) -->
                <?php if ($userRole === 'ADMIN' || $userRole === 'PILOTE'): ?>
                    <td><?= htmlspecialchars($candidature['candidate_name'] ?? 'Non renseigné'); ?></td>
                <?php endif; ?>

                <!-- Lettre de motivation -->
                <td>
                    <?php if (!empty($candidature['motivation_letter'])): ?>
                        <a href="<?= htmlspecialchars($candidature['motivation_letter']); ?>" 
                           target="_blank">Voir Lettre</a>
                    <?php else: ?>
                        Non fourni
                    <?php endif; ?>
                </td>

                <!-- CV -->
                <td>
                    <?php if (!empty($candidature['cv_path'])): ?>
                        <a href="<?= htmlspecialchars($candidature['cv_path']); ?>" 
                           target="_blank">Voir CV</a>
                    <?php else: ?>
                        Non fourni
                    <?php endif; ?>
                </td>

                <!-- Entreprise -->
                <td><?= htmlspecialchars($candidature['company_name'] ?? 'Non renseigné'); ?></td>

                <!-- Date de candidature -->
                <td><?= htmlspecialchars($candidature['date_candidature'] ?? ''); ?></td>

                <!-- Actions -->
                <td>
                    <div class="action-pill">
                        <?php 
                        // ADMIN et ETUDIANT peuvent retirer la candidature
                        if ($userRole === 'ADMIN' || $userRole === 'ETUDIANT'): ?>
                            <div class="action-part remove"
                                 onclick="if(confirm('Voulez-vous retirer cette candidature ?')) {
                                     window.location.href='index.php?controller=candidature&action=remove&id=<?= htmlspecialchars($candidature['candidature_id'] ?? ''); ?>';
                                 }">
                                <!-- Icône corbeille -->
                                <svg viewBox="0 0 24 24" fill="none" 
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 3v1H4v2h16V4h-5V3c0-.55-.45-1-1-1
                                             h-4c-.55 0-1 .45-1 1zM6 8v13c0 1.65
                                             1.35 3 3 3h6c1.65 0 3-1.35 3-3V8H6z"
                                          fill="#CF63F0"/>
                                </svg>
                                <span class="tooltip">Retirer</span>
                            </div>
                        <?php elseif ($userRole === 'PILOTE'): ?>
                            <!-- Pour le PILOTE, aucune action n'est autorisée -->
                            <div class="action-part no-action">
                                <span class="no-action-text">-</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "app/Views/layout/footer.php"; ?>
