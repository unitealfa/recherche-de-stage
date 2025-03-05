<?php include "app/Views/layout/header.php"; ?>

<?php
// Définir le titre du formulaire selon le rôle
$titleText = "Postuler à une offre";
if (isset($_SESSION['user']) && strtoupper($_SESSION['user']['role']) === 'ADMIN') {
    $titleText = "Ajouter une candidature";
}

// Vérifier si un offer_id est passé en GET
$preselectedOfferId = isset($_GET['offer_id']) ? $_GET['offer_id'] : '';

// Si la liste des offres est disponible, rechercher le titre de l'offre pré-sélectionnée
$selectedOfferTitle = '';
if ($preselectedOfferId && isset($offers) && is_array($offers)) {
    foreach($offers as $offer) {
        if ($offer['offer_id'] == $preselectedOfferId) {
            $selectedOfferTitle = $offer['title'];
            break;
        }
    }
}
?>

<h2><?= $titleText; ?></h2>
<?php if(isset($error)): ?>
  <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<form method="post" action="index.php?controller=candidature&action=postuler" enctype="multipart/form-data">
  <?php if ($preselectedOfferId): ?>
    <!-- Si une offre est pré-sélectionnée, on la place dans un champ caché et on affiche le titre -->
    <input type="hidden" name="offer_id" value="<?= htmlspecialchars($preselectedOfferId); ?>">
    <p><strong>Offre sélectionnée :</strong>
      <?php
        // Afficher le titre si disponible, sinon afficher l'ID
        echo $selectedOfferTitle ? htmlspecialchars($selectedOfferTitle) : htmlspecialchars($preselectedOfferId);
      ?>
    </p>
  <?php else: ?>
    <!-- Afficher la barre de recherche pour sélectionner une offre -->
    <label for="offerSearch">Offre :</label>
    <input type="text" id="offerSearch" list="offersList" placeholder="Rechercher une offre..." required>
    <datalist id="offersList">
      <?php foreach($offers as $offer): ?>
        <option value="<?= htmlspecialchars($offer['title']); ?>">
      <?php endforeach; ?>
    </datalist>
    <!-- Champ caché pour stocker l'ID de l'offre sélectionnée -->
    <input type="hidden" name="offer_id" id="offerId">
    
    <!-- Script pour associer le titre recherché à l'ID correspondant -->
    <script>
      // Récupérer la liste des offres depuis PHP
      const offers = <?php echo json_encode($offers); ?>;
      
      document.getElementById("offerSearch").addEventListener("change", function() {
          const searchVal = this.value.trim();
          // Recherche d'une correspondance exacte (insensible à la casse)
          const found = offers.find(o => o.title.toLowerCase() === searchVal.toLowerCase());
          if (found) {
              document.getElementById("offerId").value = found.offer_id;
          } else {
              // Si aucune offre ne correspond exactement, on vide le champ caché
              document.getElementById("offerId").value = "";
          }
      });
    </script>
  <?php endif; ?>
  
  <br>
  <!-- Pour la lettre de motivation, utiliser un champ file en imposant le format PDF -->
  <label>Lettre de motivation (PDF) :</label>
  <input type="file" name="motivation_letter" accept="application/pdf" required>
  <br>
  <!-- Pour le CV, conserver également le téléchargement de fichier PDF -->
  <label>CV (PDF) :</label>
  <input type="file" name="cv" accept="application/pdf" required>
  <br>
  <button type="submit"><?= $titleText; ?></button>
</form>
<a href="index.php?controller=candidature&action=index">Retour</a>

<?php include "app/Views/layout/footer.php"; ?>
