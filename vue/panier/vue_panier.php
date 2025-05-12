<?php
$titrePage = "Votre Panier";
require_once("includes/header.php");

error_reporting(0);

$idUser = $_SESSION['idUser'];

$livresPromotion = $unControleur->selectLivrePromotion();

$lesCommandes = $unControleur->viewSelectTotalLivreEnAttente($idUser);

$sommeAPayer = 0;

foreach ($lesCommandes as $uneCommande) {
    $idLivre = $uneCommande['idLivre'];
    $prixLivre = $uneCommande['prixLivre'];
    $quantite = $uneCommande['quantiteLigneCommande'];

    $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $idLivre);
    if (!empty($promo)) {
        $promo = current($promo);
        $prix = $promo['prixPromo'];
    } else {
        $prix = $prixLivre;
    }
    $sommeAPayer += $prix * $quantite;
}

$adresseUser = $unControleur->selectAdresseUser($idUser);
$adresseUser = $adresseUser['adresseUser'];

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
                if (isset($_POST['FiltrerLivre']) && !empty($_POST['filtre'])) {
                    $filtre = $_POST['filtre'];
                    $lesCommandes = $unControleur->selectFiltreLivreEnAttente($idUser, $filtre);
                } else {
                    $lesCommandes = $unControleur->viewSelectTotalLivreEnAttente($idUser);
                }

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
                        $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $uneCommande['idLivre']);
                        $hasPromo = !empty($promo);

                        if ($hasPromo) {
                            $promo = current($promo);
                            $prixAffichage = $promo['prixPromo'];
                            $reduction = $promo['reductionPromotion'];
                        } else {
                            $prixAffichage = $uneCommande['prixLivre'];
                        }

                        $totalLivre = $prixAffichage * $uneCommande['quantiteLigneCommande'];

                        echo "<tr>";
                        echo "<td class='px-4 py-4'>" . $uneCommande['nomLivre'] . "</td>";
                        echo "<td class='px-4 py-4'>";
                        if ($hasPromo) {
                            echo "<span class='line-through text-gray-500'>" . number_format($uneCommande['prixLivre'], 2) . "€</span><br>";
                            echo "<span class='text-red-600 font-bold'>" . number_format($prixAffichage, 2) . "€</span><br>";
                            echo "<span class='text-xs font-medium bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full'>-" . $reduction . "%</span>";
                        } else {
                            echo number_format($uneCommande['prixLivre'], 2) . "€";
                        }
                        echo "</td>";
                        echo "<td class='px-4 py-4'> * </td>";
                        echo "<td class='px-4 py-4'>" . $uneCommande['quantiteLigneCommande'] . "</td>";
                        echo "<td class='px-4 py-4'> = </td>";
                        echo "<td class='px-4 py-4'>" . number_format($totalLivre, 2) . "€</td>";
                        echo "<td class='px-4 py-4'>";
                        echo "<a href='index.php?page=3&action=sup&idCommande=" . $uneCommande['idCommande'] . "&idLigneCommande=" . $uneCommande['idLigneCommande'] . "'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6 text-red-600' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                        echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />";
                        echo "</svg>";
                        echo "</a>";
                        ?>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="idLigneCommande" value="<?php echo $uneCommande['idLigneCommande']; ?>">
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600">Modifier la quantité :</label>
                                <input type="number" name="quantiteLigneCommande" min="1"
                                       value="<?php echo $uneCommande['quantiteLigneCommande']; ?>" class="border border-gray-300 rounded w-20 p-1 text-center">
                                <button type="submit" name="ModifierPanier" value="Confirmer"
                                        class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-2 rounded transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
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