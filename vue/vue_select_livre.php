<header class="header">
    <h2> Acheter un livre </h2>
</header>
<h3> Liste des livres (<?= count($lesLivres) ?>) </h3>

<form method="post">
    Filtrer par : <input type="text" name="filtre">
    <input type="submit" name="Filtrer" value="Filtrer" class="table-success">
</form>
<br>

<table class="table" style="margin-left: 50px ; margin-right: 50px">
    <thead class="table-success">
    <tr>
        <th scope="col"></th>
        <th scope="col">Nom</th>
        <th scope="col">Catégorie</th>
        <th scope="col">Auteur</th>
        <th scope="col">Maison d'édition</th>
        <th scope="col">Nombre Exemplaires</th>
        <th scope="col">Prix</th>
        <th scope="col">Opérations</th>
    </tr>
    </thead>
    <tbody>

    <?php
    // Appel de la méthode selectLivrePromotion() pour obtenir les promotions
    $livresPromotion = $unControleur->selectLivrePromotion();

    $idUser = $_SESSION['idUser'];

    if (isset($lesLivres)) {
        foreach ($lesLivres as $unLivre) {
            // Vérification si le livre est en promotion
            $promotionTrouvee = false;
            $prixPromotion = null;

            // On parcourt les livres en promotion pour vérifier si ce livre a une promotion
            foreach ($livresPromotion as $promo) {
                if ($promo['nomLivre'] === $unLivre['nomLivre']) {
                    $promotionTrouvee = true;
                    $prixPromotion = $promo['prixPromotion']; // Récupère le prix promotionnel
                    break; // On a trouvé la promotion, on peut sortir de la boucle
                }
            }

            echo "<tr>";
            echo "<td><img src='images/livres/" . $unLivre['imageLivre'] . "' height='150' width='100'></td>";
            echo "<td>" . $unLivre['nomLivre'] . "</td>";
            echo "<td>" . $unLivre['nomCategorie'] . "</td>";
            echo "<td>" . $unLivre['auteurLivre'] . "</td>";
            echo "<td>" . $unLivre['nomMaisonEdition'] . "</td>";
            echo "<td>" . $unLivre['exemplaireLivre'] . "</td>";

            // Si le livre est en promotion, afficher le prix promotionnel et barrer le prix normal
            if ($promotionTrouvee) {
                echo "<td><del>" . $unLivre['prixLivre'] . "€</del> " . $prixPromotion . "€</td>";
            } else {
                // Si pas de promotion, afficher le prix normal
                echo "<td>" . $unLivre['prixLivre'] . "€</td>";
            }

            if (isset($isAdmin) && $isAdmin == 1) {
                echo "<td>";
                echo "<a href='index.php?page=2&action=sup&idLivre=" . $unLivre['idLivre'] . "'>" . "<img src='images/supprimer.png' height='30' width='30'> </a>";
                echo "<a href='index.php?page=2&action=edit&idLivre=" . $unLivre['idLivre'] . "'>" . "<img src='images/editer.png' height='30' width='30'> </a>";
                echo "</td>";
            }

            if (empty($isAdmin) || $isAdmin == 0) {
                echo "<td>";
                ?>
                <form method="post">
                    <table>
                        <tr>
                            <td> Entrer la quantité :</td> <br>
                            <td> <input type="text" name="insertQuantiteLivre" style="width:30px"></td>
                        </tr>
                        <tr>
                            <td> <input type="submit" name="QuantiteLivre" value="Confirmer la quantité" class="table-success"></td>
                        </tr>
                    </table>
                </form>
                <?php
                echo "<a href='index.php?page=2&action=acheter&idLivre=" . $unLivre['idLivre'] . "&idUser=" . $idUser . "'>" . "Ajouter au panier  <img src='images/acheter.png' height='30' width='30' padding-left:'50'> </a>";
                echo "</td>";
            }
            echo "</tr>";
        }
    }
    ?>

    </tbody>
</table>
<footer class="footer">
    <p>&copy; 2025 Librairie en ligne - Tous droits réservés</p>
</footer>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        padding-bottom: 70px; /* Pour éviter que le footer cache du contenu */
    }

    .header {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 20px;
    }

    h3 {
        color: #2c6e49;
        text-align: center;
        font-size: 2em;
        margin-top: 20px;
    }

    .filter-form {
        text-align: center;
        margin: 20px 0;
    }

    .filter-input {
        padding: 8px;
        font-size: 1em;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .filter-button {
        padding: 8px 15px;
        background-color: #2c6e49;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
    }

    .filter-button:hover {
        background-color: #255e40;
    }

    .table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table-success {
        background-color: #2c6e49;
        color: white;
        font-weight: bold;
    }

    .book-image {
        border-radius: 5px;
    }

    .quantity-form {
        margin: 0;
        padding: 0;
        display: inline-block;
    }

    .quantity-input {
        width: 50px;
        padding: 5px;
        font-size: 1em;
        margin-right: 10px;
        border-radius: 5px;
    }

    .add-to-cart-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #2c6e49;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.2em;
    }

    .add-to-cart-button:hover {
        background-color: #255e40;
    }

    .action-icon {
        width: 30px;
        height: 30px;
        margin: 0 5px;
        cursor: pointer;
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

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px;
    }

    .footer-banner-img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .no-books {
        text-align: center;
        font-size: 1.5em;
        color: #999;
        padding: 20px;
    }
</style>
