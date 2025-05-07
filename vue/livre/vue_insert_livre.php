<?php
$titrePage = "Ajout d'un livre";
require_once("includes/header.php");

// Initialisation de $leLivre si non défini
$leLivre = $leLivre ?? null;

$categorie = $unControleur->selectCategorie();
$maisonEdition = $unControleur->selectMaisonEdition();
$promotion = $unControleur->selectPromotion();
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

    <div class="container mx-auto px-4 py-6">
        <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">
            <?= ($leLivre != null) ? "Modifier un livre" : "Ajouter un livre" ?>
        </h3>

        <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
            <form method="post" class="space-y-6">
                <?php if ($leLivre != null): ?>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="idLivre">ID du livre</label>
                        <input type="text" name="idLivre" id="idLivre" value="<?= htmlspecialchars($leLivre['idLivre'] ?? '') ?>" readonly
                               class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomLivre">
                            Nom du livre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomLivre" id="nomLivre" value="<?= htmlspecialchars($leLivre['nomLivre'] ?? '') ?>" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="auteurLivre">
                            Auteur du livre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="auteurLivre" id="auteurLivre" value="<?= htmlspecialchars($leLivre['auteurLivre'] ?? '') ?>" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="imageLivre">
                        Lien de l'image du livre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="imageLivre" id="imageLivre" value="<?= htmlspecialchars($leLivre['imageLivre'] ?? '') ?>" required
                           class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                    <p class="text-xs text-gray-500 mt-1">Exemple: nom-du-livre.jpg</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="exemplaireLivre">
                            Nombre d'exemplaires <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="exemplaireLivre" id="exemplaireLivre" min="0" value="<?= htmlspecialchars($leLivre['exemplaireLivre'] ?? '') ?>" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prixLivre">
                            Prix du livre (€) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prixLivre" id="prixLivre" value="<?= htmlspecialchars($leLivre['prixLivre'] ?? '') ?>" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900"
                               placeholder="Utiliser . au lieu de ,">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomCategorie">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="nomCategorie" id="nomCategorie" required
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                            <option value="">-- Sélectionnez --</option>
                            <?php foreach ($categorie as $uneCategorie): ?>

                                <option value="<?= htmlspecialchars($uneCategorie['nomCategorie']) ?>"
                                    <?= ($leLivre != null && $leLivre['nomCategorie'] === $uneCategorie['nomCategorie']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($uneCategorie['nomCategorie']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomMaisonEdition">
                            Maison d'édition <span class="text-red-500">*</span>
                        </label>
                        <select name="nomMaisonEdition" id="nomMaisonEdition" required
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                            <option value="">-- Sélectionnez --</option>
                            <?php foreach ($maisonEdition as $uneMaisonEdition): ?>
                                <option value="<?= htmlspecialchars($uneMaisonEdition['nomMaisonEdition']) ?>"
                                    <?= ($leLivre != null && $leLivre['nomMaisonEdition'] === $uneMaisonEdition['nomMaisonEdition']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($uneMaisonEdition['nomMaisonEdition']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomPromotion">
                            Promotion
                        </label>
                        <select name="nomPromotion" id="nomPromotion"
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                            <option value="">-- Sélectionnez --</option>
                            <?php foreach ($promotion as $unePromotion): ?>
                                <option value="<?= htmlspecialchars($unePromotion['nomPromotion']) ?>"
                                    <?= ($leLivre != null && $leLivre['nomPromotion'] === $unePromotion['nomPromotion']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($unePromotion['nomPromotion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="reset" name="Annuler"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Annuler
                    </button>
                    <?php if ($leLivre != null): ?>
                        <button type="submit" name="Modifier"
                                class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Modifier
                        </button>
                    <?php else: ?>
                        <button type="submit" name="ValiderInsert"
                                class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Valider
                        </button>
                    <?php endif; ?>
                </div>

                <div class="text-right text-sm text-gray-500">
                    <small><span class="text-red-500">*</span> Champs obligatoires</small>
                </div>
            </form>
        </div>
    </div>
