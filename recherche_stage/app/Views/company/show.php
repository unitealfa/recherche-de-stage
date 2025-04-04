<?php include "app/Views/layout/header.php"; ?>

<h2>Détails de l'entreprise</h2>
<p>
    <img src="<?= BASE_URL ?>public/uploads/companies/<?= htmlspecialchars($company['profile_picture'] ?? 'default_pfp.png'); ?>" alt="Image de <?= htmlspecialchars($company['name']); ?>">
</p>

<p><strong>Nom :</strong> <?= htmlspecialchars($company['name']); ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($company['description'] ?? 'Non renseigné'); ?></p>
<p><strong>Email de contact :</strong> <?= htmlspecialchars($company['email_contact'] ?? 'Non renseigné'); ?></p>
<p><strong>Téléphone :</strong> <?= htmlspecialchars($company['phone_contact'] ?? 'Non renseigné'); ?></p>
<p><strong>Note moyenne :</strong> <?= htmlspecialchars($company['average_rating'] ?? 'Non notée'); ?></p>

<?php if (isset($_SESSION['user']) && in_array(strtoupper($_SESSION['user']['role']), ['ADMIN', 'PILOTE', 'ETUDIANT'])): ?>
    <h3>Évaluer l'entreprise</h3>
    <?php if(isset($error)): ?>
      <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?controller=company&action=evaluate">
        <input type="hidden" name="company_id" value="<?= htmlspecialchars($company['company_id']); ?>">
        <div class="rating">
            <?php
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
