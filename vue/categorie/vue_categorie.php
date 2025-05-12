<?php
$titrePage = "Gestion des catégories";
require_once("includes/header.php");
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

<div class="container mx-auto px-4 py-6 w-full max-w-[85%]">
    <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">Gestion des catégories</h3>

    <div class="flex flex-col lg:flex-row gap-8 justify-center">
        <div class="w-full lg:w-1/2">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">
                    Liste des catégories
                    <span class="bg-blue-900 text-white px-2 py-1 rounded-full text-sm ml-2">
                        <?= count($lesCategories) ?>
                    </span>
                </h4>
                <form method="post" class="flex items-center">
                    <input type="text" name="filtreCategorie" placeholder="Filtrer..."
                           class="border border-gray-300 rounded-l px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-900 text-sm">
                    <input type="submit" name="FiltrerCategorie" value="Filtrer"
                           class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-1 rounded-r cursor-pointer text-sm">
                </form>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nom</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($lesCategories)): ?>
                        <?php foreach ($lesCategories as $uneCategorie): ?>
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-4 py-3"><?= htmlspecialchars($uneCategorie['idCategorie']) ?></td>
                                <td class="px-4 py-3 font-medium"><?= htmlspecialchars($uneCategorie['nomCategorie']) ?></td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2">
                                        <a href="index.php?page=9&action=sup&idCategorie=<?= $uneCategorie['idCategorie'] ?>"
                                           class="text-red-600 hover:text-red-800" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="index.php?page=9&action=edit&idCategorie=<?= $uneCategorie['idCategorie'] ?>"
                                           class="text-blue-600 hover:text-blue-800" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">Aucune catégorie disponible</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full lg:w-1/2">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">
                    Liste des livres
                    <span class="bg-indigo-900 text-white px-2 py-1 rounded-full text-sm ml-2">
                        <?= count($lesLivres) ?>
                    </span>
                </h4>
                <form method="post" class="flex items-center">
                    <input type="text" name="filtreLivre" placeholder="Filtrer..."
                           class="border border-gray-300 rounded-l px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-900 text-sm">
                    <input type="submit" name="FiltrerLivre" value="Filtrer"
                           class="bg-indigo-900 hover:bg-indigo-800 text-white px-3 py-1 rounded-r cursor-pointer text-sm">
                </form>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead>
                    <tr class="bg-indigo-900 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nom</th>
                        <th class="px-4 py-2 text-left">Auteur</th>
                        <th class="px-4 py-2 text-left">Prix</th>
                        <th class="px-4 py-2 text-left">Catégorie</th>
                        <th class="px-4 py-2 text-left">Maison d'édition</th>
                        <th class="px-4 py-2 text-left">Promotion</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($lesLivres)): ?>
                        <?php foreach ($lesLivres as $unLivre): ?>
                            <tr class="border-b hover:bg-indigo-50 transition">
                                <td class="px-4 py-3"><?= htmlspecialchars($unLivre['idLivre']) ?></td>
                                <td class="px-4 py-3 font-medium"><?= htmlspecialchars($unLivre['nomLivre']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($unLivre['auteurLivre']) ?></td>
                                <td class="px-4 py-3">
                                    <?= number_format($unLivre['prixLivre'], 2) ?> €
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                        <?= htmlspecialchars($unControleur->selectNomCategorieById($unLivre['idCategorie'])[0][0]); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($unControleur->selectNomMaisonEditionById($unLivre['idMaisonEdition'])[0][0]) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($unLivre['idPromotion'])): ?>
                                        <?= htmlspecialchars($unLivre['idPromotion']) ?>
                                    <?php else: ?>
                                        <span class="text-gray-500 text-sm">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="index.php?page=9&action=associer&idLivre=<?= $unLivre['idLivre'] ?>"
                                       class="text-purple-600 hover:text-purple-800" title="Associer catégorie">
                                        <i class="fas fa-tag"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">Aucun livre disponible</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once("includes/footer.php");