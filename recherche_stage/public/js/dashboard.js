const ctx = document.getElementById('dashboardChart').getContext('2d');

const dashboardChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Mois 1', 'Mois 2', 'Mois 3', 'Mois 4', 'Mois 5', 'Mois 6'],
    datasets: [
      {
        label: 'Candidatures',
        data: applicationsData,
        backgroundColor: 'rgba(208, 118, 91, 0.8)',
        borderColor: 'rgba(208, 118, 91, 0.8)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: 'rgba(208, 118, 91, 0.8)',
        pointBorderWidth: 0,
      },
      {
        label: 'Offres de Stages',
        data: offersData,
        backgroundColor: 'rgba(175, 133, 203, 1)',
        borderColor: 'rgba(175, 133, 203, 1)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: 'rgba(175, 133, 203, 1)',
        pointBorderWidth: 0,
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false // <<<< C'est ici qu'on retire la légende du haut
      },
      tooltip: {
        titleColor: '#fff',
        bodyColor: '#fff',
        backgroundColor: '#333'
      }
    },
    scales: {
      x: {
        ticks: { color: '#aaa' },
        grid: { display: false }
      },
      y: {
        beginAtZero: true,
        ticks: { color: '#aaa' },
        grid: { color: 'rgba(255,255,255,0.1)' }
      }
    }
  }
});
