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
    <link rel="stylesheet" href="includes/css/vue_commande.css">
    <title>Liste des livres et paiement</title>
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
<?php
    require_once("includes/footer.php");
?>
</body>
</html>