<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
Vos Commandes
</header>
<?php
error_reporting(0);

$idUser = $_SESSION['idUser'];

// Récupérer les livres en promotion
$livresPromotion = $unControleur->selectLivrePromotion();

// Récupérer les livres commandés
$lesCommandes = $unControleur->viewSelectTotalLivreExpediee($idUser);

// Initialiser le montant total ajusté avec les promotions
$sommeAPayer = 0;

// Parcourir les livres commandés pour appliquer les promotions
foreach ($lesCommandes as $uneCommande) {
    $idLivre = $uneCommande['idLivre'];
    $prixLivre = $uneCommande['prixLivre'];
    $quantite = $uneCommande['quantiteLigneCommande'];

    // Vérifier si une promotion existe pour ce livre
    $promotion = null;
    foreach ($livresPromotion as $promo) {
        if ($promo['idLivre'] === $idLivre) {
            $promotion = $promo;
            break;
        }
    }

    if ($promotion && isset($promotion['prixPromotion'])) {
        // Appliquer le prix promotionnel
        $sommeAPayer += $promotion['prixPromotion'] * $quantite;
    } else {
        // Utiliser le prix normal
        $sommeAPayer += $prixLivre * $quantite;
    }
}

// Récupérer l'adresse de l'utilisateur
$adresseUser = $unControleur->selectAdresseUser($idUser);
$adresseUser = $adresseUser['adresseUser'];

// Récupérer la date de livraison
$dateCommande = $unControleur->selectDateLivraisonCommande($idUser);
$dateCommande = $dateCommande[0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des livres et paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 20px;
        }

        .table-container {
            width: 65%;
            text-align: left;
        }

        .payment-container {
            width: 30%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-left: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .table-success {
            background-color: #2E6E49 !important;
            color: white !important;
        }

        .payment-container h3 {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #0070ba;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .pay-button {
            text-align: center;
        }

        .pay-button button {
            padding: 10px 20px;
            background-color: #0070ba;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .pay-button button:hover {
            background-color: #005a93;
        }

        .star-rating {
            display: inline-block;
            direction: rtl;
            font-size: 20px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input:checked ~ label {
            color: #ffcc00;
        }

        .star-rating input:checked + label {
            color: #ffcc00;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffcc00;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
            resize: vertical;
        }

        button[type='submit'] {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type='submit']:hover {
            background-color: #218838;
        }

        .old-price {
            text-decoration: line-through;
            color: #777;
        }

        .promo-price {
            color: #e74c3c;
            font-weight: bold;
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
    </style>
</head>
<body>
<div class="main-container">
    <div class="table-container">
        <h3>Liste des livres</h3>
        <form method="post">
            Filtrer par : <input type="text" name="filtre">
            <input type="submit" name="FiltrerCommande" value="Filtrer" class="table-success">
            <br><br>
            Trier par :
            <select name="tri" onchange="this.form.submit()">
                <option value="">-- Choisir --</option>
                <option value="prixMin">Prix minimum</option>
                <option value="prixMax">Prix maximum</option>
                <option value="ordreCroissant">Ordre croissant</option>
                <option value="ordreDecroissant">Ordre décroissant</option>
            </select>
        </form>
        <br>
        <table class="table">
            <thead class="table-success">
            <tr>
                <th scope="col">Récapitulatif des commandes</th>
            </tr>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
                <th scope="col">Quantité</th>
                <th scope="col"></th>
                <th scope="col">Total Livre</th>
                <th scope="col">Avis</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $lesCommandes = $unControleur->viewSelectTotalLivreExpediee($idUser);
            $dateLigneCommande = $unControleur->selectDateLigneCommande($idUser);

            $tri = isset($_POST['tri']) ? $_POST['tri'] : '';
            $idUser = $_SESSION['idUser'];

            if ($tri == 'prixMin') {
                $lesCommandes = $unControleur->viewSelectNbMinLivreExpediee($idUser);
            } elseif ($tri == 'prixMax') {
                $lesCommandes = $unControleur->viewSelectNbMaxLivreExpediee($idUser);
            } elseif ($tri == 'ordreCroissant') {
                $lesCommandes = $unControleur->viewSelectNomMinLivreExpediee($idUser);
            } elseif ($tri == 'ordreDecroissant') {
                $lesCommandes = $unControleur->viewSelectNomMaxLivreExpediee($idUser);
            }

            if (isset($lesCommandes)) {
                foreach ($lesCommandes as $uneCommande) {
                    $promotionTrouvee = false;
                    $prixPromotion = null;

                    foreach ($livresPromotion as $promo) {
                        if ($promo['idLivre'] === $uneCommande['idLivre']) {
                            $promotionTrouvee = true;
                            $prixPromotion = $promo['prixPromotion'];
                            break;
                        }
                    }

                    if ($promotionTrouvee && isset($prixPromotion)) {
                        $totalLivre = $prixPromotion * $uneCommande['quantiteLigneCommande'];
                    } else {
                        $totalLivre = $uneCommande['prixLivre'] * $uneCommande['quantiteLigneCommande'];
                    }

                    echo "<tr>";
                    echo "<td>" . $uneCommande['nomLivre'] . "</td>";
                    echo "<td>";
                    if ($promotionTrouvee && isset($prixPromotion)) {
                        echo "<span class='old-price'>" . $uneCommande['prixLivre'] . "€</span> ";
                        echo "<span class='promo-price'>" . $prixPromotion . "€</span>";
                    } else {
                        echo $uneCommande['prixLivre'] . "€";
                    }
                    echo "</td>";
                    echo "<td> * </td>";
                    echo "<td>" . $uneCommande['quantiteLigneCommande'] . "</td>";
                    echo "<td> = </td>";
                    echo "<td>" . $totalLivre . "€</td>";

                    $nomLivre = $uneCommande['nomLivre'];
                    $idLivre = $uneCommande['idLivre'];
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='nomLivre' value='" . $nomLivre . "'>";
                    echo "<input type='hidden' name='idLivre' value='" . $idLivre . "'>";
                    echo "<div class='star-rating'>";
                    for ($i = 5; $i >= 1; $i--) {
                        $starId = 'star' . $i . '_' . $idLivre; // Ajout de l'ID du livre pour rendre l'ID unique
                        echo "<input type='radio' id='$starId' name='noteAvis' value='$i' />";
                        echo "<label for='$starId'>★</label>";
                    }
                    echo "</div>";
                    echo "<textarea name='commentaireAvis' placeholder='Écrire un avis...'></textarea>";
                    echo "<button type='submit' name='ValiderAvis'>Soumettre l'avis</button>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                }
            }
            require_once("vue/vue_insert_avis.php");
            ?>
            </tbody>
        </table>
    </div>

    <div class="payment-container">
        <h3>Information de la commande</h3>
        <form method="POST">
            <div class="form-group">
                <label for="montant">Somme payée</label>
                <input type="text" id="montant" name="montant" value="<?php
                if ($sommeAPayer > 0) {
                    echo $sommeAPayer . '€';
                } else {
                    echo '0€';
                }
                ?>" readonly>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse de livraison</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo $adresseUser; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="date-livraison">Date de livraison</label>
                <input type="date" id="date-livraison" name="date-livraison" value="<?php echo $dateCommande ?>" readonly>
            </div>
        </form>
    </div>
</div>
<footer class="footer">
    <p>&copy; 2025 Librairie en ligne - Tous droits réservés</p>
</footer>
</body>
</html>