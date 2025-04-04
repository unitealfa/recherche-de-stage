<?php include "app/Views/layout/header.php"; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>public/css/dashboard.css">

<div class="dashboard-container">
  <h1>Bienvenue sur votre Dashboard administrateur, <?= htmlspecialchars($_SESSION['user']['first_name']); ?> !</h1>

  <div class="stats-container">
    <div class="stat-card">
      <div class="icon-container bg-blue">
        <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:60%; height:60%;">
          <path d="M5.5 0C3.56717 0 2 1.56567 2 3.49804C2 5.43041 3.56717 6.99609 5.5 6.99609C7.43283 6.99609 9 5.43041 9 3.49804C9 1.56567 7.43283 0 5.5 0Z" fill="#fff"/>
          <path d="M3.5 8.99414C1.56711 8.99414 0 10.5605 0 12.4936V14.9909H11V12.4936C11 10.5605 9.43289 8.99414 7.5 8.99414H3.5Z" fill="#fff"/>
          <path d="M12.5 10H12V15H15V12.5C15 11.1193 13.8807 10 12.5 10Z" fill="#fff"/>
          <path d="M11.5 4C10.1193 4 9 5.11929 9 6.5C9 7.88071 10.1193 9 11.5 9C12.8807 9 14 7.88071 14 6.5C14 5.11929 12.8807 4 11.5 4Z" fill="#fff"/>
        </svg>
      </div>
      <div class="stat-title">Utilisateurs</div>
      <div class="stat-number"><?= $totalUsers ?></div>
      <div class="stat-change up">Comptes actifs</div>
    </div>

    <div class="stat-card">
      <div class="icon-container bg-purple">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" style="width:60%; height:60%;">
          <path fill="#fff" d="M11.47 2.152a1 1 0 0 1 1.06 0l6.904 4.315L12 10.84 4.566 6.467l6.904-4.315zM3.008 7.871A1.001 1.001 0 0 0 3 8v8a1 1 0 0 0 .47.848L11 21.554v-8.982L3.008 7.87zM13 21.554l7.53-4.706A1 1 0 0 0 21 16V8c0-.043-.003-.087-.008-.129L13 12.571v8.983z"/>
        </svg>
      </div>
      <div class="stat-title">Candidatures</div>
      <div class="stat-number"><?= $totalApplications ?></div>
      <div class="stat-change">En cours de traitement</div>
    </div>

    <div class="stat-card">
      <div class="icon-container bg-pink">
        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" style="width:60%; height:60%;">
          <path fill="#fff" d="m80.1,405.6c3.5,2.4 17,9.1 28.4-5.3l77.2-112.8 81.7,78.8c4.3,4.1 10.2,6.3 16.2,5.6 5.9-0.6 11.3-3.7 14.8-8.6l135.5-194.3 4.9,65c1.2,16.5 16.7,19.2 21.9,18.8 11.2-0.9 19.7-10.6 18.8-21.9l-8.6-114.3c-0.8-11.2-10.6-19.7-21.9-18.8l-114.3,8.6c-11.2,0.8-19.7,10.6-18.8,21.9 0.8,11.2 10.5,19.6 21.9,18.8l65-4.9-124.2,178-81.9-79c-4.3-4.2-10.3-6.3-16.2-5.6-6,0.6-11.4,3.8-14.8,8.8l-90.9,132.8c-6.4,9.3-4,22 5.3,28.4z"/>
        </svg>
      </div>
      <div class="stat-title">Offres</div>
      <div class="stat-number"><?= $totalOffers ?></div>
      <div class="stat-change">Actuellement publiées</div>
    </div>

    <div class="stat-card">
      <div class="icon-container bg-lilac">
        <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#fff" style="width:60%; height:60%;">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M13.507 12.324a7 7 0 0 0 .065-8.56A7 7 0 0 0 2 4.393V2H1v3.5l.5.5H5V5H2.811a6.008 6.008 0 1 1-.135 5.77l-.887.462a7 7 0 0 0 11.718 1.092zm-3.361-.97l.708-.707L8 7.792V4H7v4l.146.354 3 3z"/>
        </svg>
      </div>
      <div class="stat-title">Entreprises</div>
      <div class="stat-number"><?= $totalCompanies ?></div>
      <div class="stat-change up">Partenaires inscrits</div>
    </div>
  </div>

  <div class="chart-section">
    <div class="chart-container">
      <canvas id="dashboardChart"></canvas>
    </div>
    <div class="chart-legend">
      <div class="legend-item">
        <div class="legend-indicator" style="background-color: rgba(208, 118, 91, 0.8);"></div>
        <span>Candidatures</span>
      </div>
      <div class="legend-item">
        <div class="legend-indicator" style="background-color: rgba(175, 133, 203, 1);"></div>
        <span>Offres de Stages</span>
      </div>
    </div>
  </div>

  <div class="actions-container">
    <a href="index.php?controller=company&action=index" class="action-btn" style="text-decoration: none;">Gérer les entreprises</a>
    <a href="index.php?controller=offer&action=index" class="action-btn" style="text-decoration: none;">Gérer les offres de stage</a>
    <a href="index.php?controller=user&action=index" class="action-btn" style="text-decoration: none;">Gérer les utilisateurs</a>
    <a href="index.php?controller=candidature&action=index" class="action-btn" style="text-decoration: none;">Gérer les candidatures</a>
  </div>
</div>

<script>
const applicationsData = <?= json_encode($applicationsPerMonth); ?>;
const offersData = <?= json_encode($offersPerMonth); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>public/js/dashboard.js"></script>

<?php include "app/Views/layout/footer.php"; ?>
