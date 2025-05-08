<?php
$titrePage = "Bienvenue sur notre Librairie en ligne";

require_once("includes/header.php");
require_once("modele/modele.class.php");

$livresPromotion = $unControleur->selectLivrePromotion();
?>

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
<div class="container">
    <!-- Images container - on garde la structure existante -->
    <div class="images-container">
        <img src="images/livreOffert.png" height="500" width="450" class="image">
        <img src="images/accueil.png" height="400" width="800" class="image">
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
            echo "<a href='index.php?page=5' class='button-offert' style='display: inline-block; padding: 12px 24px; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; transition: background-color 0.3s;'>Obtenez votre livre offert maintenant</a>";
        }
        ?>
    </div>

    <!-- Section avec onglets pour les différentes catégories de livres -->
    <div x-data="{ activeTab: 'promo' }" class="mt-10">
        <!-- Navigation par onglets -->
        <div style="display: flex; border-bottom: 1px solid #e2e8f0; margin-bottom: 20px;">
            <button
                    @click="activeTab = 'promo'"
                    :class="{ 'tab-active': activeTab === 'promo' }"
                    style="padding: 10px 20px; font-size: 1.2em; font-weight: 500; margin-right: 10px; outline: none; background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.3s;">
                Livres en Promotion
            </button>
            <button
                    @click="activeTab = 'bestsellers'"
                    :class="{ 'tab-active': activeTab === 'bestsellers' }"
                    style="padding: 10px 20px; font-size: 1.2em; font-weight: 500; margin-right: 10px; outline: none; background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.3s;">
                Meilleures Ventes
            </button>
            <button
                    @click="activeTab = 'toprated'"
                    :class="{ 'tab-active': activeTab === 'toprated' }"
                    style="padding: 10px 20px; font-size: 1.2em; font-weight: 500; outline: none; background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.3s;">
                Meilleurs Avis
            </button>
        </div>

        <!-- Contenu de l'onglet Promotions -->
        <div x-show="activeTab === 'promo'" class="promo-section">
            <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres actuellement en Promotion</h2>
            <div class="promo-books" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php
                if (!empty($livresPromotion)) {
                    $hasPromotions = false;
                    foreach ($livresPromotion as $livre) {
                        if (!empty($livre['idPromotion'])) {
                            $hasPromotions = true;
                            $pourcentagePromo = $livre['reductionPromotion'];
                            $nouveauPrix = $livre['prixLivre'] * (1 - $pourcentagePromo / 100);
                            ?>
                            <div class="book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                                <div style="position: relative; padding: 15px;">
                                    <div style="position: absolute; top: 0; right: 0; background-color: #1A365D; color: white; padding: 5px 10px; border-radius: 0 0 0 10px; font-weight: bold;">
                                        -<?php echo $pourcentagePromo; ?>%
                                    </div>
                                    <h3 style="font-size: 1.2em; margin-bottom: 8px;">
                                        <a href="index.php?page=2&id=<?php echo $livre['idLivre']; ?>" style="text-decoration: none; transition: color 0.3s;">
                                            <?php echo htmlspecialchars($livre['nomLivre']); ?>
                                        </a>
                                    </h3>
                                    <p style="color: #718096; margin-bottom: 8px;">Par <?php echo htmlspecialchars($livre['auteurLivre']); ?></p>
                                    <p class="old-price" style="text-decoration: line-through; color: #a0aec0; margin-bottom: 5px;"><?php echo number_format($livre['prixLivre'], 2); ?> €</p>
                                    <p class="price" style="font-weight: bold; font-size: 1.3em; margin-bottom: 15px;"><?php echo number_format($nouveauPrix, 2); ?> €</p>
                                    <a href="index.php?page=2&id=<?php echo $livre['idLivre']; ?>" class="btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    if (!$hasPromotions) {
                        echo "<p style='text-align: center; color: #777; grid-column: 1 / -1;'>Aucun livre en promotion pour le moment.</p>";
                    }
                } else {
                    echo "<p style='text-align: center; color: #777; grid-column: 1 / -1;'>Aucun livre disponible.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Contenu de l'onglet Meilleures Ventes -->
        <div x-show="activeTab === 'bestsellers'" class="promo-section">
            <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres les mieux vendus</h2>
            <div class="promo-books" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php
                $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
                if (!empty($vMeilleuresVentes)) {
                    foreach ($vMeilleuresVentes as $livre) {
                        ?>
                        <div class="book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                            <div style="padding: 15px;">
                                <h3 style="font-size: 1.2em; margin-bottom: 8px;">
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
                                    <a href="index.php?page=2" class="btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='text-align: center; color: #777; grid-column: 1 / -1;'>Aucune vente enregistrée.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Contenu de l'onglet Meilleurs Avis -->
        <div x-show="activeTab === 'toprated'" class="promo-section">
            <h2 class="section-title" style="font-size: 1.8em; margin-bottom: 20px; text-align: center;">Livres les mieux notés</h2>
            <div class="promo-books" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php
                $vMeilleursAvis = $unControleur->viewMeilleursAvis();
                if (!empty($vMeilleursAvis)) {
                    foreach ($vMeilleursAvis as $livre) {
                        ?>
                        <div class="book-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                            <div style="padding: 15px;">
                                <h3 style="font-size: 1.2em; margin-bottom: 8px;">
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
                                    <a href="index.php?page=2" class="btn-details" style="display: inline-block; padding: 8px 15px; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s;">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='text-align: center; color: #777; grid-column: 1 / -1;'>Aucun avis disponible.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
</body>
</html>