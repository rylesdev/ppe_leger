<?php
$titrePage = "Gestion des maisons d'édition";
require_once("includes/header.php");

$laMaisonEdition = $laMaisonEdition ?? null;
$modeEdition = isset($laMaisonEdition);

$associerMaisonEditionLivre = $associerMaisonEditionLivre ?? null;
$modeAssociation = isset($associerMaisonEditionLivre);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-indigo-800 mb-2">Gestion des maisons d'édition</h1>
        <p class="text-gray-600 mb-6">Créez ou modifiez les maisons d'édition</p>

        <div class="flex justify-center space-x-8">
            <button id="btnInsert"
                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 transform flex items-center <?= $modeEdition ? 'hidden' : '' ?>">
                <i class="fas fa-plus-circle mr-2"></i> Nouvelle Maison d'Édition
            </button>

            <button id="btnEdit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 transform flex items-center <?= !$modeEdition ? 'hidden' : '' ?>">
                <i class="fas fa-edit mr-2"></i> Modifier Maison d'Édition
            </button>
        </div>
    </div>

    <div id="formContainer" class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto mb-10 border border-gray-100 <?= !$modeEdition ? 'hidden' : '' ?>">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-tag mr-2"></i>
            <?= $modeEdition ? 'Modifier une maison d\'édition' : 'Créer une maison d\'édition' ?>
        </h2>

        <form id="maisonEditionForm" method="post" class="space-y-6">
            <?php if ($modeEdition): ?>
                <input type="hidden" name="idMaisonEdition" value="<?= htmlspecialchars($laMaisonEdition['idMaisonEdition'] ?? '') ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="idMaisonEdition">ID de la maison d'édition</label>
                    <input type="text" value="<?= htmlspecialchars($laMaisonEdition['idMaisonEdition'] ?? '') ?>" readonly
                           class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="nomMaisonEdition">
                        <i class="fas fa-heading mr-2 text-indigo-600"></i>Nom de la maison d'édition
                    </label>
                    <input type="text" id="nomMaisonEdition" name="nomMaisonEdition"
                           value="<?= htmlspecialchars($laMaisonEdition['nomMaisonEdition'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" onclick="resetForm()"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>

                <button type="submit" name="<?= $modeEdition ? 'UpdateMaisonEdition' : 'InsertMaisonEdition' ?>"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    <?= $modeEdition ? 'Confirmer Modification' : 'Confirmer Création' ?>
                </button>
            </div>
        </form>
    </div>

    <div id="associationFormContainer" class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto mb-10 border border-gray-100 <?= !$modeAssociation ? 'hidden' : '' ?>">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-tag mr-2"></i>
            Associer Maison d'Édition Livre
        </h2>

        <form id="associationForm" method="post" class="space-y-6">
            <input type="hidden" name="idLivre" value="<?= htmlspecialchars($associerMaisonEditionLivre['idLivre'] ?? '') ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="idLivre">ID du livre</label>
                <input type="text" value="<?= htmlspecialchars($associerMaisonEditionLivre['idLivre'] ?? '') ?>" readonly
                       class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nomLivre">Nom du livre</label>
                <input type="text" value="<?= htmlspecialchars($associerMaisonEditionLivre['nomLivre'] ?? '') ?>" readonly
                       class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="nomMaisonEdition">
                    <i class="fas fa-heading mr-2 text-indigo-600"></i>Nom de la maison d'édition
                </label>
                <select name="nomMaisonEdition" id="nomMaisonEdition"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        required>
                    <option value="">-- Sélectionnez une maison d'édition --</option>
                    <?php foreach ($lesMaisonsEdition as $uneMaisonEdition): ?>
                        <option value="<?= htmlspecialchars($uneMaisonEdition['idMaisonEdition']) ?>">
                            <?= htmlspecialchars($uneMaisonEdition['nomMaisonEdition']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" onclick="resetForm()"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>

                <button type="submit" name="UpdateLivre"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Confirmer Association
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnInsert').addEventListener('click', function() {
        const formContainer = document.getElementById('formContainer');
        formContainer.classList.remove('hidden');
        document.getElementById('maisonEditionForm').reset();
        document.getElementById('btnEdit').classList.add('hidden');
    });

    function resetForm() {
        if (confirm("Annuler les modifications ?")) {
            window.location.href = 'index.php?page=8';
        }
    }
</script>

<?php
require_once("includes/footer.php");
?>
