<?php
error_reporting(0);

$idUser = $_SESSION['idUser'];

// Récupérer les livres en promotion
$livresPromotion = $unControleur->selectLivrePromotion();

// Récupérer les livres dans le panier
$lesCommandes = $unControleur->viewSelectTotalLivreEnAttente($idUser);

// Initialiser le montant total ajusté avec les promotions
$sommeAPayer = 0;

// Parcourir les livres dans le panier pour appliquer les promotions
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
            margin: 20px 50px;
        }

        .table-container {
            width: 60%;
            text-align: left;
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
            background-color: #d4edda;
        }

        .payment-container {
            width: 400px;
            height: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .payment-container h3 {
            text-align: center;
            margin-bottom: 20px;
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

        .old-price {
            text-decoration: line-through;
            color: #777;
        }

        .promo-price {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="table-container">
        <h3>Liste des livres</h3>
        <form method="post">
            Filtrer par : <input type="text" name="filtre">
            <input type="submit" name="FiltrerPanier" value="Filtrer" class="table-success">
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
                <th scope="col">Récapitulatif de la commande</th>
            </tr>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
                <th scope="col">Quantité</th>
                <th scope="col"></th>
                <th scope="col">Total Livre</th>
                <th scope="col">Opération</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $lesCommandes = $unControleur->viewSelectTotalLivreEnAttente($idUser);

            $tri = isset($_POST['tri']) ? $_POST['tri'] : '';
            $idUser = $_SESSION['idUser'];

            if ($tri == 'prixMin') {
                $lesCommandes = $unControleur->viewSelectNbMinLivreEnAttente($idUser);
            } elseif ($tri == 'prixMax') {
                $lesCommandes = $unControleur->viewSelectNbMaxLivreEnAttente($idUser);
            } elseif ($tri == 'ordreCroissant') {
                $lesCommandes = $unControleur->viewSelectNomMinLivreEnAttente($idUser);
            } elseif ($tri == 'ordreDecroissant') {
                $lesCommandes = $unControleur->viewSelectNomMaxLivreEnAttente($idUser);
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
                    echo "<td>";
                    echo "<a href='index.php?page=3&action=sup&idCommande=" . $uneCommande['idCommande'] . "&idLigneCommande=" . $uneCommande['idLigneCommande'] . "'>" . "<img src='images/supprimer.png' height='30' width='30'> </a>";
                    echo "<a href='index.php?page=3&action=edit&idCommande=" . $uneCommande['idCommande'] . "&idLigneCommande=" . $uneCommande['idLigneCommande'] . "'>" . "<img src='images/editer.png' height='30' width='30'> </a>";
                    ?>
                    <form method="post">
                        <table>
                            <tr>
                                <td> Modifier la quantité :</td> <br>
                                <td> <input type="text" name="updateQuantiteLivre" style="width:30px"></td>
                            </tr>
                            <tr>
                                <td> <input type="submit" name="QuantitePanier" value="Confirmer" class="table-success"></td>
                            </tr>
                        </table>
                    </form>
                    <?php
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="payment-container">
        <h3>Paiement de la commande</h3>
        <form action="index.php?page=3&action=payer" method="post">
            <div class="form-group">
                <label for="montant">Somme à payer</label>
                <input type="text" id="montant" name="montant" value="<?php
                if ($sommeAPayer > 0) {
                    echo $sommeAPayer . '€';
                    $pointAUtiliser = $sommeAPayer * 10;
                } else {
                    echo '0€';
                }
                ?>" readonly>
            </div>
            <div class="form-group">
                <label for="montant">Points à utiliser</label>
                <input type="text" id="point" name="point" value="<?php
                if ($pointAUtiliser > 0) {
                    echo $pointAUtiliser . ' points';
                    $_SESSION['pointAUtiliser'] = $pointAUtiliser;
                } else {
                    echo '0 Point';
                }
                ?>" readonly>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse de livraison</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo $adresseUser; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="date-livraison">Date de livraison</label>
                <input type="date" id="date-livraison" name="date-livraison" value="<?php echo $dateCommande; ?>" readonly>
            </div>
            <div class="pay-button">
                <?php
                echo "<input type='submit' name='PayerPaypal' value='Payer avec Paypal' class='btn btn-primary' style='margin-right: 10px;'>";
                echo "<input type='submit' name='PayerPoint' value='Payer avec des points' class='btn btn-primary'>";
                ?>
            </div>
        </form>
    </div>
</div>
</body>
</html>