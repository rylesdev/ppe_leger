<?php
$titrePage = "Gestion des promotions";
require_once("includes/header.php");
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

    <div class="container mx-auto px-4 py-6">
        <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">Liste des promotions <span class="bg-blue-900 text-white px-2 py-1 rounded-full text-sm"><?= count($lesPromotions) ?></span></h3>

        <form method="post" class="mb-6 flex justify-center">
            <div class="flex items-center">
                <label class="mr-2 text-gray-700">Filtrer par : </label>
                <input type="text" name="filtre" class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                <input type="submit" name="FiltrerPromotion" value="Filtrer" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-r cursor-pointer transition duration-200">
            </div>
        </form>

        <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
            <table class="w-full">
                <thead>
                <tr class="bg-blue-900 text-white">
                    <th scope="col" class="px-6 py-3 text-left">ID</th>
                    <th scope="col" class="px-6 py-3 text-left">Nom</th>
                    <th scope="col" class="px-6 py-3 text-left">ID Livre</th>
                    <th scope="col" class="px-6 py-3 text-left">Nom Livre</th>
                    <th scope="col" class="px-6 py-3 text-left">Date Début</th>
                    <th scope="col" class="px-6 py-3 text-left">Date Fin</th>
                    <th scope="col" class="px-6 py-3 text-left">Réduction</th>
                    <th scope="col" class="px-6 py-3 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($lesPromotions)) {
                    $rowCount = 0;
                    foreach ($lesPromotions as $unePromotion) {
                        // Alternance des couleurs de fond pour les lignes
                        $bgColor = $rowCount % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                        $rowCount++;

                        // Vérification si la promotion est active
                        $dateActuelle = date('Y-m-d');
                        $dateDebut = $unePromotion['dateDebutPromotion'];
                        $dateFin = $unePromotion['dateFinPromotion'];
                        $estActive = ($dateActuelle >= $dateDebut && $dateActuelle <= $dateFin);

                        echo "<tr class='{$bgColor} hover:bg-blue-50 transition duration-150'>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($unePromotion['idPromotion']) . "</td>";
                        echo "<td class='px-6 py-4 font-medium text-gray-800'>" . htmlspecialchars($unePromotion['nomPromotion']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($unePromotion['idLivre'] ?? '') . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($unePromotion['nomLivre'] ?? '') . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($dateDebut) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($dateFin) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($unePromotion['reductionPromotion']) . "%</td>";
                        echo "<td class='px-6 py-4'>";
                        echo "<div class='flex space-x-2'>";
                        echo "<a href='index.php?page=7&action=sup&idPromotion=" . $unePromotion['idPromotion'] . "&idLivre=" . $unePromotion['idLivre'] . "' class='bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded' title='Supprimer'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                        echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />";
                        echo "</svg>";
                        echo "</a>";
                        echo "<a href='index.php?page=7&action=edit&idPromotion=" . $unePromotion['idPromotion'] . "&idLivre=" . $unePromotion['idLivre'] . "' class='bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded' title='Éditer'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                        echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' />";
                        echo "</svg>";
                        echo "</a>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='px-6 py-4 text-center text-gray-500'>Aucune promotion disponible</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
require_once("includes/footer.php");
