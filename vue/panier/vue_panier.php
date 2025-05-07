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
        $prix = $promo['prixPromo']; // Utiliser le prixPromo calculé
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
<link rel="stylesheet" href="includes/css/vue_panier.css">

<div class="container mx-auto px-4 py-6 flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
    <div class="w-full md:w-3/4">
        <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">Liste des livres</h3>
        <form method="post" class="mb-6 flex justify-center">
            <div class="flex items-center">
                <label class="mr-2 text-gray-700">Filtrer par : </label>
                <input type="text" name="filtre" class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                <input type="submit" name="FiltrerLivre" value="Filtrer" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-r cursor-pointer transition duration-200">
            </div>
        </form>
        <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
            <table class="w-full">
                <thead>
                <tr class="bg-blue-900 text-white">
                    <th scope="col" class="px-4 py-3 text-left">Nom</th>
                    <th scope="col" class="px-4 py-3 text-left">Prix</th>
                    <th scope="col" class="px-4 py-3 text-left"></th>
                    <th scope="col" class="px-4 py-3 text-left">Quantité</th>
                    <th scope="col" class="px-4 py-3 text-left"></th>
                    <th scope="col" class="px-4 py-3 text-left">Total Livre</th>
                    <th scope="col" class="px-4 py-3 text-left">Opération</th>
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
                        echo "<td class='px-4 py-4'>" . $uneCommande['nomLivre'] . "</td>";
                        echo "<td class='px-4 py-4'>";
                        if ($hasPromo) {
                            // Afficher l'ancien prix barré et le nouveau prix en promotion
                            echo "<span class='old-price line-through'>" . number_format($uneCommande['prixLivre'], 2) . "€</span> ";
                            echo "<span class='promo-price text-red-600'>" . number_format($prixAffichage, 2) . "€ (-" . $reduction . "%)</span>";
                        } else {
                            echo number_format($uneCommande['prixLivre'], 2) . "€";
                        }
                        echo "</td>";
                        echo "<td class='px-4 py-4'> * </td>";
                        echo "<td class='px-4 py-4'>" . $uneCommande['quantiteLigneCommande'] . "</td>";
                        echo "<td class='px-4 py-4'> = </td>";
                        echo "<td class='px-4 py-4'>" . number_format($totalLivre, 2) . "€</td>";
                        echo "<td class='px-4 py-4'>";
                        echo "<a href='index.php?page=3&action=sup&idCommande=" . $uneCommande['idCommande'] . "&idLigneCommande=" . $uneCommande['idLigneCommande'] . "'>" . "<img src='images/supprimer.png' height='30' width='30'> </a>";
                        ?>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="idLigneCommande" value="<?php echo $uneCommande['idLigneCommande']; ?>">
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600">Modifier la quantité :</label>
                                <input type="number" name="quantiteLigneCommande" min="1"
                                       value="<?php echo $uneCommande['quantiteLigneCommande']; ?>" class="border border-gray-300 rounded w-20 p-1 text-center">
                                <button type="submit" name="ModifierPanier" value="Confirmer"
                                        class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-2 rounded transition duration-200">
                                    Confirmer
                                </button>
                            </div>
                        </form>
                        <?php
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='px-4 py-4 text-center text-gray-500'>Aucun livre dans le panier</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="w-full md:w-1/4">
        <div class="payment-container p-6 bg-white shadow-lg rounded-lg border border-gray-200">
            <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">Paiement de la commande</h3>
            <form action="index.php?page=3&action=payer" method="post">
                <div class="form-group mb-4">
                    <label for="montant" class="block text-gray-700">Somme à payer</label>
                    <input type="text" id="montant" name="montant" value="<?php
                    if ($sommeAPayer > 0) {
                        echo number_format($sommeAPayer, 2) . '€';
                        $pointAUtiliser = (int) $sommeAPayer * 10;
                    } else {
                        echo '0.00€';
                    }
                    ?>" readonly class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                </div>
                <div class="form-group mb-4">
                    <label for="point" class="block text-gray-700">Points à utiliser</label>
                    <input type="text" id="point" name="point" value="<?php
                    if ($pointAUtiliser > 0) {
                        echo $pointAUtiliser . ' points';
                        $_SESSION['pointAUtiliser'] = $pointAUtiliser;
                    } else {
                        echo '0 point';
                    }
                    ?>" readonly class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                </div>
                <div class="form-group mb-4">
                    <label for="adresse" class="block text-gray-700">Adresse de livraison</label>
                    <input type="text" id="adresse" name="adresse" value="<?php echo $adresseUser; ?>" readonly class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                </div>
                <div class="form-group mb-4">
                    <label for="date-livraison" class="block text-gray-700">Date de livraison</label>
                    <input type="date" id="date-livraison" name="date-livraison" value="<?php echo $dateCommande; ?>" readonly class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                </div>
                <div class="pay-button text-center">
                    <input type="submit" name="PayerPaypal" value="Payer avec Paypal" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded cursor-pointer transition duration-200 mr-2">
                    <input type="submit" name="PayerPoint" value="Payer avec des points" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded cursor-pointer transition duration-200">
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once("includes/footer.php");
?>
