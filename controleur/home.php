<?php
$titrePage = "Bienvenue sur notre Librairie en ligne";
$cssPage = "home";
require_once("includes/header.php");

require_once("modele/modele.class.php");

$livresPromotion = $unControleur->selectLivrePromotion();
?>

<link rel="stylesheet" href="includes/css/home.css">
<div class="container">
    <div class="images-container">
        <img src="images/livreOffert.png" height="500" width="450" class="image">
        <img src="images/accueil.png" height="400" width="800" class="image">
    </div>

    <div class="livre-offert-section">
        <?php
        $livresOfferts = $unControleur->selectOffrirLivre($_SESSION['idUser']);
        if (!empty($livresOfferts)) {
            echo "<h2>Félicitations ! Vous avez un/des livre(s) offert(s) :</h2>";
            foreach ($livresOfferts as $livre) {
                echo "- " . htmlspecialchars($livre['nomLivre']) . "<br>";
            }
            if (isset($_SESSION['livreOffert'])) {
                echo "<h2>" . htmlspecialchars($_SESSION['livreOffert']) . "</h2>";
                unset($_SESSION['livreOffert']);
            }
        } else {
            echo "<h2>Profitez de notre offre exceptionnelle sur un livre offert !</h2>";
            echo "<a href='index.php?page=5' class='button-offert'>Obtenez votre livre offert maintenant</a>";
        }
        ?>
    </div>

    <div class="promo-section">
        <h2>Livres actuellement en Promotion</h2>
        <div class="promo-books">
            <?php
            if (!empty($livresPromotion)) {
                $hasPromotions = false;
                foreach ($livresPromotion as $livre) {
                    if (!empty($livre['idPromotion'])) {
                        $hasPromotions = true;
                        $pourcentagePromo = $livre['reductionPromotion']; // Utilisation de reductionPromotion au lieu de idPromotion
                        $nouveauPrix = $livre['prixLivre'] * (1 - $pourcentagePromo / 100);

                        echo "<div class='book-card'>";
                        echo "<h3><a href='index.php?page=2&id=".$livre['idLivre']."'>" . htmlspecialchars($livre['nomLivre']) . "</a></h3>";
                        echo "<p>Auteur: " . htmlspecialchars($livre['auteurLivre']) . "</p>";
                        echo "<p class='old-price'>Ancien prix: " . number_format($livre['prixLivre'], 2) . "€</p>";
                        echo "<p class='price'>Nouveau prix: " . number_format($nouveauPrix, 2) . "€ (-$pourcentagePromo%)</p>";
                        echo "</div>";
                    }
                }
                if (!$hasPromotions) {
                    echo "<p>Aucun livre en promotion pour le moment.</p>";
                }
            } else {
                echo "<p>Aucun livre disponible.</p>";
            }
            ?>
        </div>
    </div>

    <div class="promo-section">
        <h2>Livres les mieux vendus</h2>
        <div class="promo-books">
            <?php
            $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
            if (!empty($vMeilleuresVentes)) {
                foreach ($vMeilleuresVentes as $livre) {
                    echo "<div class='book-card'>";
                    echo "<h3><a href='index.php?page=2'>" . htmlspecialchars($livre['nomLivre']) . "</a></h3>";
                    echo "<p>Vendus : <strong>" . intval($livre['totalVendu']) . "</strong></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucune vente enregistrée.</p>";
            }
            ?>
        </div>
    </div>

    <div class="promo-section">
        <h2>Livres les mieux notés</h2>
        <div class="promo-books">
            <?php
            $vMeilleursAvis = $unControleur->viewMeilleursAvis();
            if (!empty($vMeilleursAvis)) {
                foreach ($vMeilleursAvis as $livre) {
                    echo "<div class='book-card'>";
                    echo "<h3><a href='index.php?page=2'>" . htmlspecialchars($livre['nomLivre']) . "</a></h3>";
                    echo "<p>Note moyenne : <strong>" . number_format($livre['moyenneNote'], 1) . "/5</strong></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun avis disponible.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
</body>
</html>