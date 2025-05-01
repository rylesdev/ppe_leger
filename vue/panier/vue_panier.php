<?php
$titrePage = "Votre Panier";
require_once("includes/header.php");

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
    $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $idLivre);
    if (!empty($promo)) {
        $promo = current($promo);
        $prix = $promo['prixPromo']; // Utilisation du prixPromo calculé
    } else {
        $prix = $prixLivre;
    }
    $sommeAPayer += $prix * $quantite;
}

// Récupérer l'adresse de l'utilisateur
$adresseUser = $unControleur->selectAdresseUser($idUser);
$adresseUser = $adresseUser['adresseUser'];

// Récupérer la date de livraison
$dateCommande = $unControleur->selectDateLivraisonCommande($idUser);
$dateCommande = $dateCommande[0];
?>

    <link rel="stylesheet" href="includes/css/vue_panier.css">

    <div class="main-container">
        <div class="table-container">
            <h3>Liste des livres</h3>
            <form method="post">
                Filtrer par : <input type="text" name="filtre">
                <input type="submit" name="FiltrerLivre" value="Filtrer" class="table-success">
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
                // Appliquer le filtre si nécessaire
                if (isset($_POST['FiltrerLivre']) && !empty($_POST['filtre'])) {
                    $filtre = $_POST['filtre'];
                    $lesCommandes = $unControleur->selectFiltreLivreEnAttente($idUser, $filtre);
                } else {
                    $lesCommandes = $unControleur->viewSelectTotalLivreEnAttente($idUser);
                }

                // Appliquer le tri si nécessaire
                $tri = isset($_POST['tri']) ? $_POST['tri'] : '';

                if ($tri == 'prixMin') {
                    $lesCommandes = $unControleur->viewSelectNbMinLivreEnAttente($idUser);
                } elseif ($tri == 'prixMax') {
                    $lesCommandes = $unControleur->viewSelectNbMaxLivreEnAttente($idUser);
                } elseif ($tri == 'ordreCroissant') {
                    $lesCommandes = $unControleur->viewSelectNomMinLivreEnAttente($idUser);
                } elseif ($tri == 'ordreDecroissant') {
                    $lesCommandes = $unControleur->viewSelectNomMaxLivreEnAttente($idUser);
                }

                if (isset($lesCommandes) && !empty($lesCommandes)) {
                    foreach ($lesCommandes as $uneCommande) {
                        // Vérifier si le livre a une promotion
                        $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $uneCommande['idLivre']);
                        $hasPromo = !empty($promo);

                        if ($hasPromo) {
                            $promo = current($promo);
                            $prixAffichage = $promo['prixPromo'];
                            $reduction = $promo['reductionPromotion'];
                        } else {
                            $prixAffichage = $uneCommande['prixLivre'];
                        }

                        // Calcul du total avec le prix promotionnel si disponible
                        $totalLivre = $prixAffichage * $uneCommande['quantiteLigneCommande'];

                        echo "<tr>";
                        echo "<td>" . $uneCommande['nomLivre'] . "</td>";
                        echo "<td>";
                        if ($hasPromo) {
                            // Afficher l'ancien prix barré et le nouveau prix en promotion
                            echo "<span class='old-price'>" . number_format($uneCommande['prixLivre'], 2) . "€</span> ";
                            echo "<span class='promo-price'>" . number_format($prixAffichage, 2) . "€ (-" . $reduction . "%)</span>";
                        } else {
                            echo number_format($uneCommande['prixLivre'], 2) . "€";
                        }
                        echo "</td>";
                        echo "<td> * </td>";
                        echo "<td>" . $uneCommande['quantiteLigneCommande'] . "</td>";
                        echo "<td> = </td>";
                        echo "<td>" . number_format($totalLivre, 2) . "€</td>";
                        echo "<td>";
                        echo "<a href='index.php?page=3&action=sup&idCommande=" . $uneCommande['idCommande'] . "&idLigneCommande=" . $uneCommande['idLigneCommande'] . "'>" . "<img src='images/supprimer.png' height='30' width='30'> </a>";
                        ?>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="idLigneCommande" value="<?php echo $uneCommande['idLigneCommande']; ?>">
                            <table>
                                <tr>
                                    <td>Modifier la quantité :</td>
                                    <td>
                                        <input type="number" name="quantiteLigneCommande" min="1"
                                               value="<?php echo $uneCommande['quantiteLigneCommande']; ?>" style="width: 50px;" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type="submit" name="ModifierPanier" value="Confirmer"
                                                style="background-color: #2E6E49; color: white; border: none; padding: 5px 10px; cursor: pointer;">
                                            Confirmer
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <?php
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>Aucun livre dans le panier</td></tr>";
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
                        echo number_format($sommeAPayer, 2) . '€';
                        $pointAUtiliser = (int) $sommeAPayer * 10;
                    } else {
                        echo '0.00€';
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
                        echo '0 point';
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
<?php
require_once("includes/footer.php");
?>