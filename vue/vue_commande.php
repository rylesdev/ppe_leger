<?php
error_reporting(0);

$idUser = $_SESSION['idUser'];

$totalCommande = $unControleur->selectViewTotalCommandeExpediee($idUser);

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
            background-color: #d4edda;
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
<div class="main-container">
    <div class="table-container">
        <h3>Liste des livres</h3>
        <form method="post">
            Filtrer par : <input type="text" name="filtre">
            <input type="submit" name="Filtrer" value="Filtrer" class="table-success">
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
            $lesCommandes = $unControleur->selectViewTotalLivreExpediee($idUser);
            $dateLigneCommande = $unControleur->selectDateLigneCommande($idUser);

            $tri = isset($_POST['tri']) ? $_POST['tri'] : '';
            $idUser = $_SESSION['idUser'];

            if ($tri == 'prixMin') {
                $lesCommandes = $unControleur->selectViewNbMinLivreExpediee($idUser);
            } elseif ($tri == 'prixMax') {
                $lesCommandes = $unControleur->selectViewNbMaxLivreExpediee($idUser);
            } elseif ($tri == 'ordreCroissant') {
                $lesCommandes = $unControleur->selectViewNomMinLivreExpediee($idUser);
            } elseif ($tri == 'ordreDecroissant') {
                $lesCommandes = $unControleur->selectViewNomMaxLivreExpediee($idUser);
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

                    $nomLivre = $uneCommande['nomLivre'];
                    $idLivre = $uneCommande['idLivre'];
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='nomLivre' value='".$nomLivre."'>";
                    echo "<input type='hidden' name='idLivre' value='".$idLivre."'>";
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<label>";
                        echo "<input type='radio' name='noteAvis' value='".$i."' /> ★";
                        echo "</label>";
                    }
                    echo "<textarea name='commentaireAvis' placeholder='Écrire un avis...'></textarea>";
                    echo "<button type='submit' name='ValiderAvis'>Soumettre l'avis</button>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                }
            }
            require_once("vue/vue_insert_avis.php")
            ?>
            </tbody>
        </table>
    </div>

    <div class="payment-container">
        <h3>Information de la commande</h3>
        <form action="process_payment.php" method="POST">
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
</body>
</html>