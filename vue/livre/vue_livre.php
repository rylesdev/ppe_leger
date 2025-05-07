<?php
$titrePage = "Acheter un livre";
require_once("includes/header.php");
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

    <div class="container mx-auto px-4 py-6">
        <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">Liste des livres <span class="bg-blue-900 text-white px-2 py-1 rounded-full text-sm"><?= count($lesLivres) ?></span></h3>

        <form method="post" class="mb-6 flex justify-center">
            <div class="flex items-center">
                <label class="mr-2 text-gray-700">Filtrer par : </label>
                <input type="text" name="filtre" class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                <input type="submit" name="FiltrerLivre" value="Filtrer" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-r cursor-pointer transition duration-200">
            </div>
        </form>

        <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
            <table class="w-full" style="table-layout: fixed;">
                <thead>
                <tr class="bg-blue-900 text-white">
                    <th scope="col" class="px-4 py-3 text-left" style="width: 100px">Image</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 200px">Nom</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 150px">Catégorie</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 150px">Auteur</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 150px">Maison d'édition</th>
                    <th scope="col" class="px-4 py-3 text-center" style="width: 100px">Exemplaires</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 120px">Prix</th>
                    <th scope="col" class="px-4 py-3 text-left" style="width: 250px">Opérations</th> <!-- Augmenté de 200px à 250px -->
                </tr>
                </thead>
                <tbody>
                <?php
                // Récupération des promotions indexées par idLivre pour optimisation
                $livresPromotion = $unControleur->selectLivrePromotion();
                $promosParId = [];
                foreach ($livresPromotion as $promo) {
                    $promosParId[$promo['idLivre']] = $promo;
                }

                $idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : null;

                if (isset($lesLivres)) {
                    $rowCount = 0;
                    foreach ($lesLivres as $unLivre) {
                        $enPromotion = isset($promosParId[$unLivre['idLivre']]);
                        $prixPromo = $enPromotion ?
                            $unLivre['prixLivre'] * (1 - $promosParId[$unLivre['idLivre']]['reductionPromotion'] / 100) :
                            null;

                        // Alternance des couleurs de fond pour les lignes
                        $bgColor = $rowCount % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                        $rowCount++;

                        echo "<tr class='{$bgColor} hover:bg-blue-50 transition duration-150'>";

                        // Image du livre
                        echo "<td class='px-4 py-4' style='width: 100px'>";
                        echo "<div class='flex justify-center'>";
                        echo "<img src='images/livres/" . htmlspecialchars($unLivre['imageLivre']) . "' class='h-36 w-24 object-cover rounded shadow' alt='Couverture du livre'>";
                        echo "</div>";
                        echo "</td>";

                        // Nom du livre
                        echo "<td class='px-4 py-4 font-medium text-gray-800' style='width: 200px'>" . htmlspecialchars($unLivre['nomLivre']) . "</td>";

                        // Catégorie
                        echo "<td class='px-4 py-4' style='width: 150px'>";
                        echo "<span class='px-2 py-1 bg-blue-100 text-blue-900 rounded-full text-xs font-medium'>" . htmlspecialchars($unLivre['nomCategorie']) . "</span>";
                        echo "</td>";

                        // Auteur
                        echo "<td class='px-4 py-4 text-gray-700' style='width: 150px'>" . htmlspecialchars($unLivre['auteurLivre']) . "</td>";

                        // Maison d'édition
                        echo "<td class='px-4 py-4 text-gray-700' style='width: 150px'>" . htmlspecialchars($unLivre['nomMaisonEdition']) . "</td>";

                        // Exemplaires
                        $stockClass = '';
                        if ($unLivre['exemplaireLivre'] > 10) {
                            $stockClass = 'text-green-600';
                        } elseif ($unLivre['exemplaireLivre'] > 0) {
                            $stockClass = 'text-yellow-600';
                        } else {
                            $stockClass = 'text-red-600';
                        }

                        echo "<td class='px-4 py-4 text-center font-medium {$stockClass}' style='width: 100px'>" . htmlspecialchars($unLivre['exemplaireLivre']) . "</td>";

                        // Prix avec gestion de la promotion
                        echo "<td class='px-4 py-4' style='width: 120px'>";
                        if ($enPromotion) {
                            $reduction = $promosParId[$unLivre['idLivre']]['reductionPromotion'];
                            echo "<div>";
                            echo "<span class='line-through text-gray-500'>" . number_format($unLivre['prixLivre'], 2) . "€</span><br>";
                            echo "<span class='text-red-600 font-bold'>" . number_format($prixPromo, 2) . "€</span><br>";
                            echo "<span class='text-xs font-medium bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full'>-" . $reduction . "%</span>";
                            echo "</div>";
                        } else {
                            echo "<span class='font-medium'>" . number_format($unLivre['prixLivre'], 2) . "€</span>";
                        }
                        echo "</td>";

                        // Opérations (admin ou utilisateur)
                        echo "<td class='px-4 py-4' style='width: 250px; white-space: nowrap'>"; // Largeur augmentée
                        if (isset($isAdmin) && $isAdmin == 1) {
                            // Boutons admin
                            echo "<div class='flex space-x-2'>";
                            echo "<a href='index.php?page=2&action=sup&idLivre=" . $unLivre['idLivre'] . "' class='bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded' title='Supprimer'>";
                            echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />";
                            echo "</svg>";
                            echo "</a>";
                            echo "<a href='index.php?page=2&action=edit&idLivre=" . $unLivre['idLivre'] . "' class='bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded' title='Éditer'>";
                            echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' />";
                            echo "</svg>";
                            echo "</a>";
                            echo "</div>";
                        } else {
                            // Vérifier si l'utilisateur est connecté
                            if (!isset($_SESSION['emailUser'])) {
                                echo "<p class='text-red-600 font-medium'>Vous devez être connecté </p>";
                                echo "<p class='text-red-600 font-medium'>pour acheter un livre.</p>";
                            } else {
                                // Formulaire utilisateur - Réorganisé pour plus d'espace
                                echo "<form method='post' class='flex flex-col space-y-2'>"; // Changé en flex-col
                                echo "<input type='hidden' name='idLivre' value='".$unLivre['idLivre']."'>";
                                echo "<input type='hidden' name='idUser' value='".$idUser."'>";

                                // Conteneur pour quantité et bouton
                                echo "<div class='flex items-center space-x-2'>";
                                echo "<label class='text-sm text-gray-600'>Quantité:</label>";
                                echo "<input type='number' name='quantiteLivre' min='1' max='".$unLivre['exemplaireLivre']."' value='1' class='border border-gray-300 rounded w-20 p-1 text-center'>"; // Largeur augmentée à w-20
                                echo "</div>";

                                echo "<button type='submit' name='action' value='AjouterPanier' class='bg-blue-900 hover:bg-blue-800 text-white px-3 py-2 rounded flex items-center justify-center transition duration-200 w-full'>"; // Pleine largeur
                                echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 mr-1' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                                echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' />";
                                echo "</svg>";
                                echo "Ajouter au panier";
                                echo "</button>";
                                echo "</form>";
                            }
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='px-4 py-4 text-center text-gray-500'>Aucun livre disponible</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
require_once("includes/footer.php");