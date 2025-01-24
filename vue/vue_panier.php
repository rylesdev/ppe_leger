<?php
error_reporting(0);

$idUser = $_SESSION['idUser'];

$totalCommande = $unControleur->selectViewTotalCommande($idUser);

$sommeAPayer = $totalCommande['totalCommande'];

$adresseUser = $unControleur->selectAdresseUser($idUser);
$adresseUser = $adresseUser['adresseUser'];

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

        .table-container {
            margin: 20px 50px;
            width: 50%;
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
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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
    </style>
</head>
<body>
<div class="payment-container">
    <h3>Paiement de la commande</h3>
    <form action="process_payment.php" method="POST">
        <div class="form-group">
            <label for="montant">Somme à payer</label>
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
            <input type="date" id="date-livraison" name="date-livraison" value ="<?php echo $dateCommande ?>" readonly>
        </div>
        <div class="pay-button">
            <?php
            echo "<a href='index.php?page=3&action=payer'> Payer avec Paypal </a>";
            ?>
        </div>
    </form>
</div>

<div class="table-container">
    <h3>Liste des livres</h3>
    <form method="post">
        Filtrer par : <input type="text" name="filtre">
        <input type="submit" name="Filtrer" value="Filtrer" class="table-success">
        <br><br>
        Trier par :
        <select name="tri" onchange="this.form.submit()">
            <option value="">-- Choisir --</option>
            <option value="prix_min">Prix minimum</option>
            <option value="prix_max">Prix maximum</option>
            <option value="ordre_croissant">Ordre croissant</option>
            <option value="ordre_decroissant">Ordre décroissant</option>
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
        $lesCommandes = $unControleur->selectViewTotalLivre($idUser);

        $tri = isset($_POST['tri']) ? $_POST['tri'] : '';
        $idUser = $_SESSION['idUser'];

        if ($tri == 'prix_min') {
            $lesCommandes = $unControleur->selectViewNbMinLivre();
        } elseif ($tri == 'prix_max') {
            $lesCommandes = $unControleur->selectViewNbMaxLivre();
        } elseif ($tri == 'ordre_croissant') {
            $lesCommandes = $unControleur->selectViewNomMinLivre();
        } elseif ($tri == 'ordre_decroissant') {
            $lesCommandes = $unControleur->selectViewNomMaxLivre();
        }

        if (isset($lesCommandes)){
            foreach ($lesCommandes as $uneCommande) {
                echo "<tr>";
                echo "<td>".$uneCommande['nomLivre']."</td>";
                echo "<td>".$uneCommande['prixLivre']."€</td>";
                echo "<td> * </td>";
                echo "<td>".$uneCommande['quantiteLigneCommande']."</td>";
                echo "<td> = </td>";
                echo "<td>".$uneCommande['totalLivre']."€</td>";
                echo "<td>";
                echo "<a href='index.php?page=3&action=sup&idCommande=" . $uneCommande['idCommande'] . "'>" . "<img src='images/supprimer.png' heigth='30' width='30'> </a>";
                echo "<a href='index.php?page=3&action=edit&idCommande=" . $uneCommande['idCommande'] . "'>" . "<img src='images/editer.png' heigth='30' width='30'> </a>";
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
</body>
</html>
