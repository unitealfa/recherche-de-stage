<?php include "app/Views/layout/header.php"; ?>

<h2>Créer une offre de stage</h2>

<?php if(isset($error)): ?>
    <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<form method="post" action="index.php?controller=offer&action=create" enctype="multipart/form-data">
    <fieldset>
        <legend>Entreprise</legend>
        <label>Entreprise :</label>
        <select name="company_id" required>
            <option value="">Sélectionnez une entreprise</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= htmlspecialchars($company['company_id']); ?>">
                    <?= htmlspecialchars($company['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </fieldset>
    
    <fieldset>
        <legend>Détails de l'offre</legend>
        <label>Titre :</label>
        <input type="text" name="title" placeholder="Stage en quoi ?" required>
        <br>
        <label>Description :</label>
        <textarea name="description" required></textarea>
        <br>
        <label>Compétences :</label>
        <input type="text" name="competencies">
        <br>
        <label>Rémunération :</label>
        <input type="number" step="0.01" name="remuneration_base">
        <br>
        <label>Date début :</label>
        <input type="date" name="start_date">
        <br>
        <label>Date fin :</label>
        <input type="date" name="end_date">
        <br>
    </fieldset>
    
    <button type="submit">Créer l'offre</button>
</form>

<a href="index.php?controller=offer&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
