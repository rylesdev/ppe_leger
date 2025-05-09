<?php
$titrePage = "Vos Commandes";
require_once("includes/header.php");error_reporting(0);$idUser = $_SESSION['idUser'];// Récupérer les données nécessaires
$toutesLesCommandes = $unControleur->selectCommandeByUser($idUser);
$livresPromotion = $unControleur->selectLivrePromotion();// Gestion des paramètres
$idCommandeSelectionnee = $_POST['idCommandeSelectionnee'] ?? null;
$tri = $_POST['tri'] ?? null;// Récupérer les commandes selon sélection et tri
if ($idCommandeSelectionnee) {
    $lesCommandes = $unControleur->selectCommandeByIdTri($idCommandeSelectionnee, $tri);
} else {
    $lesCommandes = $unControleur->selectCommandeTri($idUser, $tri);
}// Calcul du montant total avec promotions
$sommeAPayer = 0;
foreach ($lesCommandes as $uneCommande) {
    $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $uneCommande['idLivre']);
    if (!empty($promo)) {
        $promo = current($promo);
        $prix = $promo['prixPromo']; // Utilisation du prixPromo calculé
    } else {
        $prix = $uneCommande['prixLivre'];
    }
    $sommeAPayer += $prix * $uneCommande['quantiteLigneCommande'];
}// Récupérer les infos utilisateur
$adresseUser = $unControleur->selectAdresseUser($idUser)['adresseUser'];
$dateCommande = $unControleur->selectDateLivraisonCommande($idUser)[0];

?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="includes/css/style.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionner tous les groupes d'étoiles
            const starGroups = document.querySelectorAll('.star-rating');

            starGroups.forEach(group => {
                const stars = group.querySelectorAll('label');
                const inputs = group.querySelectorAll('input[type="radio"]');

                // Ajouter des événements à chaque étoile
                stars.forEach((star, index) => {
                    // Au clic sur une étoile
                    star.addEventListener('click', function() {
                        // Réinitialiser toutes les étoiles
                        stars.forEach(s => s.classList.remove('text-yellow-400'));
                        stars.forEach(s => s.classList.add('text-gray-300'));

                        // Colorier les étoiles de gauche à droite jusqu'à celle cliquée (incluse)
                        for (let i = 0; i <= index; i++) {
                            stars[i].classList.remove('text-gray-300');
                            stars[i].classList.add('text-yellow-400');
                        }

                        // Cocher explicitement le bouton radio correspondant
                        inputs[index].checked = true;
                    });
                });
            });
        });
    </script>

    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
        <!-- Colonne principale - Détails des commandes -->
        <div class="w-full md:w-3/4">
            <div class="command-actions mb-6 p-4 bg-white shadow rounded-lg">
                <form method="post" class="space-y-4">
                    <div class="form-group">
                        <label for="idCommandeSelectionnee" class="block text-gray-700">Sélectionner une commande :</label>
                        <select name="idCommandeSelectionnee" id="idCommandeSelectionnee" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                            <option value="">Toutes mes commandes</option>
                            <?php foreach ($toutesLesCommandes as $cmd):
                                $nbArticles = $unControleur->countLigneCommande($cmd['idCommande'])[0][0];
                                $dateFormatee = date('d/m/Y à H:i', strtotime($cmd['dateCommande']));
                                ?>
                                <option value="<?= $cmd['idCommande'] ?>" <?= ($cmd['idCommande'] == $idCommandeSelectionnee) ? 'selected' : '' ?>>
                                    Commande du <?= $dateFormatee ?> (<?= $nbArticles ?> articles)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tri" class="block text-gray-700">Trier par :</label>
                        <select name="tri" id="tri" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900">
                            <option value="">-- Non trié --</option>
                            <option value="prixMin" <?= ($tri == 'prixMin') ? 'selected' : '' ?>>Prix croissant</option>
                            <option value="prixMax" <?= ($tri == 'prixMax') ? 'selected' : '' ?>>Prix décroissant</option>
                            <option value="ordreCroissant" <?= ($tri == 'ordreCroissant') ? 'selected' : '' ?>>A-Z</option>
                            <option value="ordreDecroissant" <?= ($tri == 'ordreDecroissant') ? 'selected' : '' ?>>Z-A</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-apply bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800 transition duration-200">Appliquer</button>
                </form>
            </div>

            <h2 class="text-2xl font-bold text-center mb-6 text-indigo-900">Détails des commandes</h2>
            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-3 text-left">Livre</th>
                        <th class="px-4 py-3 text-left">Prix unitaire</th>
                        <th class="px-4 py-3 text-left">Quantité</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Avis</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lesCommandes as $uneCommande):
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
                        ?>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4"><?= htmlspecialchars($uneCommande['nomLivre']) ?></td>
                            <td class="px-4 py-4">
                                <?php if ($hasPromo): ?>
                                    <span class="old-price line-through"><?= number_format($uneCommande['prixLivre'], 2) ?>€</span>
                                    <span class="promo-price text-red-600"><?= number_format($prixAffichage, 2) ?>€ (-<?= $reduction ?>%)</span>
                                <?php else: ?>
                                    <?= number_format($uneCommande['prixLivre'], 2) ?>€
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4"><?= $uneCommande['quantiteLigneCommande'] ?></td>
                            <td class="px-4 py-4"><?= number_format($totalLivre, 2) ?>€</td>
                            <td class="px-4 py-4">
                                <!-- CORRECTION: Identifier chaque formulaire de manière unique -->
                                <form method="post" class="avis-form space-y-2" id="form_<?= $uneCommande['idLivre'] ?>">
                                    <input type="hidden" name="idLivre" value="<?= $uneCommande['idLivre'] ?>">
                                    <input type="hidden" name="nomLivre" value="<?= htmlspecialchars($uneCommande['nomLivre']) ?>">
                                    <div class="star-rating flex">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" id="star<?= $i ?>_<?= $uneCommande['idLivre'] ?>" name="noteAvis" value="<?= $i ?>" class="hidden">
                                            <label for="star<?= $i ?>_<?= $uneCommande['idLivre'] ?>" class="star-label text-gray-300 text-2xl cursor-pointer hover:text-yellow-400 px-1">★</label>
                                        <?php endfor; ?>
                                    </div>
                                    <textarea name="commentaireAvis" placeholder="Votre avis..." class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-900"></textarea>
                                    <button type="submit" name="ValiderAvis" class="btn-submit bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800 transition duration-200">Envoyer l'avis</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Colonne latérale - Récapitulatif -->
        <div class="w-full md:w-1/4">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-lg shadow-lg border border-blue-200 transform transition-all duration-300 hover:shadow-xl">
                <h3 class="text-2xl font-bold mb-4 text-center text-blue-900 border-b pb-2 border-blue-200">Récapitulatif</h3>

                <div class="space-y-4 mt-4">
                    <div class="flex items-center p-3 bg-white rounded-lg shadow">
                        <div class="p-2 mr-4 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total payé</p>
                            <p class="font-bold text-lg text-blue-900"><?= number_format($sommeAPayer, 2) ?>€</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-white rounded-lg shadow">
                        <div class="p-2 mr-4 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Adresse de livraison</p>
                            <p class="font-medium text-blue-900"><?= htmlspecialchars($adresseUser) ?></p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-white rounded-lg shadow">
                        <div class="p-2 mr-4 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date de livraison</p>
                            <p class="font-medium text-blue-900"><?= $dateCommande ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php
require_once("includes/footer.php");
?>