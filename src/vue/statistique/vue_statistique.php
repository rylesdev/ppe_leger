<?php
$titrePage = "Statistiques";
require_once("includes/header.php");

$idUser = $_SESSION['idUser'];
$vCommandesEnAttente = $unControleur->viewSelectCommandesEnAttente() ?? [['nbCommandeEnAttente' => 0]];
$vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes() ?? [];
$vLivresEnStock = $unControleur->viewSelectLivresEnStock() ?? [];
$vMeilleursAvis = $unControleur->viewMeilleursAvis() ?? [];
$vNbLivreAcheteUser = $unControleur->viewNbLivreAcheteUser() ?? [];

$meilleuresVentesLabels = array_column($vMeilleuresVentes, 'nomLivre');
$meilleuresVentesData = array_column($vMeilleuresVentes, 'totalVendu');
$meilleursAvisLabels = array_column($vMeilleursAvis, 'nomLivre');
$meilleursAvisData = array_column($vMeilleursAvis, 'moyenneNote');
?>

    <link rel="stylesheet" href="includes/css/vue_statistique.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="statistics-container">
        <div class="header-section">
            <h2>Tableau de Bord Statistique</h2>
            <p class="last-update">Mis à jour le <?= date('d/m/Y à H:i') ?></p>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-header">
                    <i class="fas fa-clock"></i>
                    <h3>Commandes en attente</h3>
                </div>
                <div class="kpi-value"><?= $vCommandesEnAttente[0]['nbCommandeEnAttente'] ?: 'Aucune' ?></div>
                <p class="kpi-desc">Nombre de commandes non traitées</p>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Stocks critiques</h3>
                </div>
                <div class="kpi-value"><?= count($vLivresEnStock) ?: 'Aucun' ?></div>
                <p class="kpi-desc">Livres avec moins de 5 exemplaires</p>
            </div>
        </div>

        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <i class="fas fa-chart-line"></i>
                    <h3>Top 5 des ventes</h3>
                </div>
                <div class="chart-container" style="height:250px">
                    <?php if(!empty($meilleuresVentesData)): ?>
                        <canvas id="ventesChart"></canvas>
                    <?php else: ?>
                        <div class="no-data-chart">Aucune donnée de vente disponible</div>
                    <?php endif; ?>
                </div>
                <div class="chart-footer">
                    <span>Total des exemplaires vendus par titre</span>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <i class="fas fa-star"></i>
                    <h3>Notes moyennes</h3>
                </div>
                <div class="chart-container" style="height:250px">
                    <?php if(!empty($meilleursAvisData)): ?>
                        <canvas id="notesChart"></canvas>
                    <?php else: ?>
                        <div class="no-data-chart">Aucune évaluation disponible</div>
                    <?php endif; ?>
                </div>
                <div class="chart-footer">
                    <span>Moyenne sur 5 étoiles</span>
                </div>
            </div>
        </div>

        <div class="data-grid">
            <div class="data-card">
                <div class="data-header">
                    <i class="fas fa-box-open"></i>
                    <h3>Stocks critiques</h3>
                </div>
                <div class="table-container">
                    <?php if(!empty($vLivresEnStock)): ?>
                        <table>
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Stock</th>
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($vLivresEnStock as $livre): ?>
                                <tr>
                                    <td><?= htmlspecialchars($livre['nomLivre']) ?></td>
                                    <td><?= $livre['exemplaireLivre'] ?></td>
                                    <td>
                                    <span class="badge <?= $livre['exemplaireLivre'] < 2 ? 'critical' : 'warning' ?>">
                                        <?= $livre['exemplaireLivre'] < 2 ? 'CRITIQUE' : 'FAIBLE' ?>
                                    </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data-table">Aucun stock critique actuellement</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="data-card">
                <div class="data-header">
                    <i class="fas fa-users"></i>
                    <h3>Clients actifs</h3>
                </div>
                <div class="table-container">
                    <?php if(!empty($vNbLivreAcheteUser)): ?>
                        <table>
                            <thead>
                            <tr>
                                <th>Client</th>
                                <th>Achats</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($vNbLivreAcheteUser as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars(explode('@', $client['emailUser'])[0]) ?></td>
                                    <td><?= $client['nbLivreAchete'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data-table">Aucun client actif répertorié</div>
                    <?php endif; ?>
                </div>
                <div class="data-footer">
                    <p>Nombre total d'achats par client</p>
                </div>
            </div>
        </div>
    </div>

<?php if(!empty($meilleuresVentesData)): ?>
    <script>
        new Chart(document.getElementById('ventesChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($meilleuresVentesLabels) ?>,
                datasets: [{
                    label: 'Ventes',
                    data: <?= json_encode($meilleuresVentesData) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' ventes';
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
<?php endif; ?>

<?php if(!empty($meilleursAvisData)): ?>
    <script>
        new Chart(document.getElementById('notesChart'), {
            type: 'radar',
            data: {
                labels: <?= json_encode($meilleursAvisLabels) ?>,
                datasets: [{
                    label: 'Note moyenne',
                    data: <?= json_encode($meilleursAvisData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { display: true },
                        suggestedMin: 0,
                        suggestedMax: 5
                    }
                }
            }
        });
    </script>
<?php endif; ?>

<?php require_once('includes/footer.php'); ?>