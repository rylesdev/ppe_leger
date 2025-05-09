<?php
$titrePage = "Bienvenue sur notre Librairie en ligne";

require_once("includes/header.php");
require_once("modele/modele.class.php");

$livresPromotion = $unControleur->selectLivrePromotion();
?>

<!-- Ajout du CDN de Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- On garde le fichier CSS original pour préserver la navbar et autres styles existants -->
<link rel="stylesheet" href="includes/css/home.css">

<!-- Ajout du CDN de Tailwind CSS avec configuration pour ne pas perturber les styles existants -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
      corePlugins: {
        preflight: false, // Désactive les styles de base de Tailwind qui pourraient perturber votre CSS existant
      }
    }
</script>

<!-- Styles personnalisés avec la nouvelle couleur bleu foncé #1A365D -->
<style>
    /* Remplacements de couleurs */
    .header { background-color: #1A365D !important; }
    .book-card h3 { color: #1A365D !important; }
    .book-card h3 a { color: #1A365D !important; }
    .footer { background-color: #1A365D !important; }
    .button-offert { background-color: #2C5282 !important; }
    .button-offert:hover { background-color: #1A365D !important; }

    /* Styles pour les nouveaux éléments */
    .tab-active { border-bottom-color: #1A365D !important; color: #1A365D !important; }
    .btn-details { background-color: #1A365D !important; }
    .section-title { color: #1A365D !important; }
    .price { color: #1A365D !important; }

    /* Styles spécifiques pour les badges et indicateurs */
    .promo-badge { background-color: #1A365D; color: white; }
    .best-seller-badge { background-color: #EBF8FF; color: #2C5282; }
    .rating-star { color: #2C5282; }
</style>

<!-- Ajout d'Alpine.js pour l'interactivité -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

<!-- Contenu principal -->
<div class="container d-flex justify-content-center">
    <div class="w-75">
        <!-- Carrousel de livres à la une -->
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center">
                        <img src="images/livres/programmer_en_java.png" class="d-block" alt="Programmer en Java" style="max-width: 200px; height: auto;">
                    </div>
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); border-radius: 10px; padding: 10px;">
                        <h5 style="font-size: 1.2em;">Programmer en Java</h5>
                        <p style="font-size: 0.9em;">Découvrez les bases de la programmation en Java.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center">
                        <img src="images/livres/les_pensees.png" class="d-block" alt="Les Pensées" style="max-width: 200px; height: auto;">
                    </div>
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); border-radius: 10px; padding: 10px;">
                        <h5 style="font-size: 1.2em;">Les Pensées</h5>
                        <p style="font-size: 0.9em;">Explorez les pensées philosophiques.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center">
                        <img src="images/livres/la_peste.png" class="d-block" alt="La Peste" style="max-width: 200px; height: auto;">
                    </div>
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); border-radius: 10px; padding: 10px;">
                        <h5 style="font-size: 1.2em;">La Peste</h5>
                        <p style="font-size: 0.9em;">Un classique de la littérature française.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Section livre offert - style mis à jour -->
        <div class="livre-offert-section" style="background-color: #f0f5ff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <?php
            $livresOfferts = $unControleur->selectOffrirLivre($_SESSION['idUser']);
            if (!empty($livresOfferts)) {
                echo "<h2 style='color: #1A365D; font-size: 2em;'>Félicitations ! Vous avez un/des livre(s) offert(s) :</h2>";
                echo "<ul style='list-style-type: none; padding: 0;'>";
                foreach ($livresOfferts as $livre) {
                    echo "<li style='margin: 10px 0; font-size: 1.2em; color: #2C5282;'>- " . htmlspecialchars($livre['nomLivre']) . "</li>";
                }
                echo "</ul>";
                if (isset($_SESSION['livreOffert'])) {
                    echo "<h2 style='color: #2C5282;'>" . htmlspecialchars($_SESSION['livreOffert']) . "</h2>";
                    unset($_SESSION['livreOffert']);
                }
            } else {
                echo "<h2 style='color: #1A365D; font-size: 2em;'>Profitez de notre offre exceptionnelle sur un livre offert !</h2>";
                echo "<a href='index.php?page=5' class='button-offert btn btn-primary' style='display: inline-block; padding: 12px 24px; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; transition: background-color 0.3s;'>Obtenez votre livre offert maintenant</a>";
            }
            ?>
        </div>

        <!-- Section avec onglets pour les différentes catégories de livres -->
        <div x-data="{ activeTab: 'promo' }" class="mt-10">
            <!-- Navigation par onglets -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button @click="activeTab = 'promo'" :class="{ 'active': activeTab === 'promo' }" class="nav-link" style="font-size: 1.2em; font-weight: 500; outline: none; cursor: pointer; transition: all 0.3s;">
                        Livres en Promotion
                    </button>
                </li>
                <li class="nav-item">
                    <button @click="activeTab = 'bestsellers'" :class="{ 'active': activeTab === 'bestsellers' }" class="nav-link" style="font-size: 1.2em; font-weight: 500; outline: none; cursor: pointer; transition: all 0.3s;">
                        Meilleures Ventes
                    </button>
                </li>
                <li class="nav-item">
                    <button @click="activeTab = 'toprated'" :class="{ 'active': activeTab === 'toprated' }" class="nav-link" style="font-size: 1.2em; font-weight: 500; outline: none; cursor: pointer; transition: all 0.3s;">
                        Meilleurs Avis
                    </button>
                </li>
            </ul>

            <!-- Contenu de l'onglet Promotions -->
            <div x-show="activeTab === 'promo'" class="promo-section">
                <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres actuellement en Promotion</h2>
                <div class="promo-books row">
                    <?php
                    if (!empty($livresPromotion)) {
                        $hasPromotions = false;
                        foreach ($livresPromotion as $livre) {
                            if (!empty($livre['idPromotion'])) {
                                $hasPromotions = true;
                                $pourcentagePromo = $livre['reductionPromotion'];
                                $nouveauPrix = $livre['prixLivre'] * (1 - $pourcentagePromo / 100);
                                ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                                        <div class="card-body" style="position: relative; padding: 15px;">
                                            <div style="position: absolute; top: 0; right: 0; background-color: #1A365D; color: white; padding: 5px 10px; border-radius: 0 0 0 10px; font-weight: bold;">
                                                -<?php echo $pourcentagePromo; ?>%
                                            </div>
                                            <h3 class="card-title" style="font-size: 1.2em; margin-bottom: 8px;">
                                                <a href="index.php?page=2&id=<?php echo $livre['idLivre']; ?>" style="text-decoration: none; transition: color 0.3s;">
                                                    <?php echo htmlspecialchars($livre['nomLivre']); ?>
                                                </a>
                                            </h3>
                                            <p class="card-text" style="color: #718096; margin-bottom: 8px;">Par <?php echo htmlspecialchars($livre['auteurLivre']); ?></p>
                                            <p class="old-price" style="text-decoration: line-through; color: #a0aec0; margin-bottom: 5px;"><?php echo number_format($livre['prixLivre'], 2); ?> €</p>
                                            <p class="price" style="font-weight: bold; font-size: 1.3em; margin-bottom: 15px;"><?php echo number_format($nouveauPrix, 2); ?> €</p>
                                            <a href="index.php?page=2&id=<?php echo $livre['idLivre']; ?>" class="btn btn-primary btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        if (!$hasPromotions) {
                            echo "<p class='text-center text-muted w-100'>Aucun livre en promotion pour le moment.</p>";
                        }
                    } else {
                        echo "<p class='text-center text-muted w-100'>Aucun livre disponible.</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Contenu de l'onglet Meilleures Ventes -->
            <div x-show="activeTab === 'bestsellers'" class="promo-section">
                <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres les mieux vendus</h2>
                <div class="promo-books row">
                    <?php
                    $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
                    if (!empty($vMeilleuresVentes)) {
                        foreach ($vMeilleuresVentes as $livre) {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                                    <div class="card-body" style="padding: 15px;">
                                        <h3 class="card-title" style="font-size: 1.2em; margin-bottom: 8px;">
                                            <a href="index.php?page=2" style="text-decoration: none; transition: color 0.3s;">
                                                <?php echo htmlspecialchars($livre['nomLivre']); ?>
                                            </a>
                                        </h3>
                                        <div style="margin: 15px 0; display: inline-block; padding: 5px 10px; background-color: #EBF8FF; color: #2C5282; border-radius: 20px; font-weight: 500;">
                                            <span style="display: inline-flex; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                <?php echo intval($livre['totalVendu']); ?> vendus
                                            </span>
                                        </div>
                                        <div>
                                            <a href="index.php?page=2" class="btn btn-primary btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center text-muted w-100'>Aucune vente enregistrée.</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Contenu de l'onglet Meilleurs Avis -->
            <div x-show="activeTab === 'toprated'" class="promo-section">
                <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres les mieux notés</h2>
                <div class="promo-books row">
                    <?php
                    $vMeilleursAvis = $unControleur->viewMeilleursAvis();
                    if (!empty($vMeilleursAvis)) {
                        foreach ($vMeilleursAvis as $livre) {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                                    <div class="card-body" style="padding: 15px;">
                                        <h3 class="card-title" style="font-size: 1.2em; margin-bottom: 8px;">
                                            <a href="index.php?page=2" style="text-decoration: none; transition: color 0.3s;">
                                                <?php echo htmlspecialchars($livre['nomLivre']); ?>
                                            </a>
                                        </h3>
                                        <div style="margin: 15px 0; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: #2C5282; margin-right: 5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                                </svg>
                                            </span>
                                            <span style="font-weight: bold; font-size: 1.2em;"><?php echo number_format($livre['moyenneNote'], 1); ?>/5</span>
                                        </div>
                                        <div>
                                            <a href="index.php?page=2" class="btn btn-primary btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center text-muted w-100'>Aucun avis disponible.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ajout du script Bootstrap pour le carrousel -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once("includes/footer.php"); ?>
</body>
</html>