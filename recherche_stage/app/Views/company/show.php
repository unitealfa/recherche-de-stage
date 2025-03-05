<?php include "app/Views/layout/header.php"; ?>

<h2>Détails de l'entreprise</h2>

<p><strong>ID :</strong> <?= htmlspecialchars($company['company_id']); ?></p>
<p><strong>Nom :</strong> <?= htmlspecialchars($company['name']); ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($company['description'] ?? 'Non renseigné'); ?></p>
<p><strong>Email de contact :</strong> <?= htmlspecialchars($company['email_contact'] ?? 'Non renseigné'); ?></p>
<p><strong>Téléphone :</strong> <?= htmlspecialchars($company['phone_contact'] ?? 'Non renseigné'); ?></p>
<p><strong>Note moyenne :</strong> <?= htmlspecialchars($company['average_rating'] ?? 'Non notée'); ?></p>

<!-- Formulaire d'évaluation par étoiles -->
<?php if (isset($_SESSION['user']) && in_array(strtoupper($_SESSION['user']['role']), ['ADMIN', 'PILOTE', 'ETUDIANT'])): ?>
    <h3>Évaluer l'entreprise</h3>
    <?php if(isset($error)): ?>
      <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?controller=company&action=evaluate">
        <!-- Champ caché pour l'ID de l'entreprise -->
        <input type="hidden" name="company_id" value="<?= htmlspecialchars($company['company_id']); ?>">
        
        <!-- Bloc de notation par étoiles -->
        <div class="rating">
            <?php
            // Génère 5 boutons radio pour une note de 5 à 1
            for ($i = 5; $i >= 1; $i--) {
                echo '<input type="radio" id="star' . $i . '" name="rating" value="' . $i . '" required>';
                echo '<label for="star' . $i . '">★</label>';
            }
            ?>
        </div>
        <button type="submit">Envoyer l'évaluation</button>
    </form>
<?php endif; ?>

<a href="index.php?controller=company&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
