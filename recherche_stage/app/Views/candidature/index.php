<?php include "app/Views/layout/header.php"; ?>

<h2>Candidatures</h2>

<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<?php 
// Récupérer le rôle de l'utilisateur depuis la session
$userRole = strtoupper($_SESSION['user']['role'] ?? '');
?>

<?php 
// Afficher le bouton d'ajout uniquement pour les étudiants et le bouton "Ajouter une offre" pour les pilotes.
if ($userRole === 'ETUDIANT'): ?>
    <a href="index.php?controller=candidature&action=postuler" class="btn-add" style="display:inline-block; margin-bottom:10px; padding:5px 10px; background:#4CAF50; color:#fff; text-decoration:none;">Ajouter une candidature</a>
<?php elseif ($userRole === 'PILOTE'): ?>
    <a href="index.php?controller=offer&action=create" class="btn-add" style="display:inline-block; margin-bottom:10px; padding:5px 10px; background:#2196F3; color:#fff; text-decoration:none;">Ajouter une offre</a>
<?php endif; ?>

<table border="1" cellspacing="0" cellpadding="5">
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
    <?php foreach ($candidatures as $candidature): ?>
        <tr>
            <td><?= htmlspecialchars($candidature['offer_title'] ?? 'Non renseigné'); ?></td>
            <?php if ($userRole === 'ADMIN' || $userRole === 'PILOTE'): ?>
                <td><?= htmlspecialchars($candidature['candidate_name'] ?? 'Non renseigné'); ?></td>
            <?php endif; ?>
            <td>
                <?php if (!empty($candidature['motivation_letter'])): ?>
                    <a href="<?= htmlspecialchars($candidature['motivation_letter']); ?>" target="_blank">Voir Lettre</a>
                <?php else: ?>
                    Non fourni
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($candidature['cv_path'])): ?>
                    <a href="<?= htmlspecialchars($candidature['cv_path']); ?>" target="_blank">Voir CV</a>
                <?php else: ?>
                    Non fourni
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($candidature['company_name'] ?? 'Non renseigné'); ?></td>
            <td><?= htmlspecialchars($candidature['date_candidature'] ?? ''); ?></td>
            <td>
                <?php if ($userRole === 'ADMIN'): ?>
                    <!-- Pour l'ADMIN, seule l'action "Retirer" est affichée -->
                    <a href="index.php?controller=candidature&action=remove&id=<?= htmlspecialchars($candidature['candidature_id'] ?? ''); ?>" onclick="return confirm('Voulez-vous retirer cette candidature ?');">Retirer</a>
                <?php elseif ($userRole === 'ETUDIANT'): ?>
                    <!-- Pour l'étudiant, l'action "Retirer" est disponible -->
                    <a href="index.php?controller=candidature&action=remove&id=<?= htmlspecialchars($candidature['candidature_id'] ?? ''); ?>" onclick="return confirm('Voulez-vous retirer cette candidature ?');">Retirer</a>
                <?php elseif ($userRole === 'PILOTE'): ?>
                    <!-- Pour le PILOTE, aucune action n'est autorisée -->
                    -
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="index.php?controller=offer&action=index">Retour à la liste des offres</a>

<?php include "app/Views/layout/footer.php"; ?>
