<?php
require_once("modele/modele.class.php");

session_start();

$livresPromotion = $unControleur->selectLivrePromotion();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librairie en ligne</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #2E6E49;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            color: white !important;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .images-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .image {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .promo-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .promo-section h2 {
            font-size: 2em;
            text-align: center;
            color: #5c4033; /* Marron clair */
            margin-bottom: 20px;
        }

        .promo-books {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .book-card {
            background-color: #fff;
            border-radius: 8px;
            width: 250px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-10px);
        }

        .book-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .book-card h3 {
            color: #2c6e49; /* Vert foncé */
            font-size: 1.5em;
            margin: 10px 0;
        }

        .book-card p {
            color: #777;
            font-size: 1em;
            margin-bottom: 10px;
        }

        .book-card .price {
            font-size: 1.2em;
            color: #e74c3c; /* Rouge */
            font-weight: bold;
        }

        .book-card .old-price {
            font-size: 1.2em;
            color: #777;
            text-decoration: line-through;
            margin-bottom: 10px;
        }

        .footer {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .button-offert {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745; /* Vert */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .button-offert:hover {
            background-color: #218838; /* Vert foncé */
        }

        .livre-offert-section {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<header class="header">
    <h1>Bienvenue sur notre Librairie en ligne</h1>
</header>

<div class="container">
    <div class="images-container">
        <img src="images/livreOffert.png" height="500" width="450" class="image">
        <img src="images/accueil.png" height="400" width="800" class="image">
    </div>

    <div class="livre-offert-section">
        <?php
        $livresOfferts = $unControleur->selectOffrirLivre($_SESSION['idUser']);
        if (isset($livresOfferts) && $livresOfferts != null) {
            echo "<h2>Félicitations ! Vous avez un/des livre(s) offert(s) : </h2>";
            foreach ($livresOfferts as $livre) {
                $result = $unControleur->selectOffrirLivre($_SESSION['idUser']);
                $livre = $livre['nomLivre'];
                echo "- " . $livre . ", ";
                echo "<h2>" . $_SESSION['livreOffert'] . "</h2>";
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
            if (isset($livresPromotion) && count($livresPromotion) > 0) {
                foreach ($livresPromotion as $livre) {
                    echo "<div class='book-card'>";
                    echo "<h3><a href='index.php?page=2'>" . $livre['nomLivre'] . "</a></h3>";
                    echo "<p class='old-price'>" . $livre['prixLivre'] . "€</p>";
                    echo "<p class='price'>" . $livre['prixPromotion'] . "€</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun livre en promotion pour le moment.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Nouvelle section : Livres les mieux vendus -->
    <div class="promo-section">
        <h2>Livres les mieux vendus</h2>
        <div class="promo-books">
            <?php
            $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
            if (isset($vMeilleuresVentes) && count($vMeilleuresVentes) > 0) {
                foreach ($vMeilleuresVentes as $livre) {
                    echo "<div class='book-card'>";
                    echo "<h3><a href='index.php?page=2'>" . $livre['nomLivre'] . "</a></h3>";
                    echo "<p>Vendus : <strong>" . $livre['totalVendu'] . "</strong></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucune vente enregistrée.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Nouvelle section : Meilleurs avis -->
    <div class="promo-section">
        <h2>Livres les mieux notés</h2>
        <div class="promo-books">
            <?php
            $vMeilleursAvis = $unControleur->viewMeilleursAvis();
            if (isset($vMeilleursAvis) && count($vMeilleursAvis) > 0) {
                foreach ($vMeilleursAvis as $livre) {
                    echo "<div class='book-card'>";
                    echo "<h3><a href='index.php?page=2'>" . $livre['nomLivre'] . "</a></h3>";
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

<footer class="footer">
    <p>&copy; 2025 Librairie en ligne - Tous droits réservés</p>
</footer>

</body>
</html>