<?php
$titrePage = "Gestion des promotions";
require_once("includes/header.php");

$laPromotion = $laPromotion ?? null;
$modeEdition = isset($laPromotion);

$associerPromoLivre = $associerPromoLivre ?? null;
$modeAssociation = isset($associerPromoLivre);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-indigo-800 mb-2">Gestion des promotions</h1>
        <p class="text-gray-600 mb-6">Créez ou modifiez les promotions en cours</p>

        <div class="flex justify-center space-x-8">
            <button id="btnInsert"
                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 transform flex items-center <?= $modeEdition ? 'hidden' : '' ?>">
                <i class="fas fa-plus-circle mr-2"></i> Nouvelle Promotion
            </button>

            <button id="btnEdit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 transform flex items-center <?= !$modeEdition ? 'hidden' : '' ?>">
                <i class="fas fa-edit mr-2"></i> Modifier Promotion
            </button>
        </div>
    </div>

    <div id="formContainer" class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto mb-10 border border-gray-100 <?= !$modeEdition ? 'hidden' : '' ?>">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-tag mr-2"></i>
            <?= $modeEdition ? 'Modifier une promotion' : 'Créer une promotion' ?>
        </h2>

        <form id="promoForm" method="post" class="space-y-6">
            <?php if ($modeEdition): ?>
                <input type="hidden" name="idPromotion" value="<?= htmlspecialchars($laPromotion['idPromotion'] ?? '') ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="idPromotion">ID de la promotion</label>
                    <input type="text" value="<?= htmlspecialchars($laPromotion['idPromotion'] ?? '') ?>" readonly
                           class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="nomPromotion">
                        <i class="fas fa-heading mr-2 text-indigo-600"></i>Nom de la promotion
                    </label>
                    <input type="text" id="nomPromotion" name="nomPromotion"
                           value="<?= htmlspecialchars($laPromotion['nomPromotion'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2" for="dateDebut">
                            <i class="far fa-calendar-alt mr-2 text-indigo-600"></i>Date de début
                        </label>
                        <input type="date" id="dateDebut" name="dateDebutPromotion"
                               value="<?= htmlspecialchars($laPromotion['dateDebutPromotion'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2" for="dateFin">
                            <i class="far fa-calendar-check mr-2 text-indigo-600"></i>Date de fin
                        </label>
                        <input type="date" id="dateFin" name="dateFinPromotion"
                               value="<?= htmlspecialchars($laPromotion['dateFinPromotion'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="reduction">
                        <i class="fas fa-percent mr-2 text-indigo-600"></i>Réduction (%)
                    </label>
                    <div class="relative">
                        <input type="number" id="reduction" name="reductionPromotion" min="1" max="100"
                               value="<?= htmlspecialchars($laPromotion['reductionPromotion'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 pl-10"
                               required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" onclick="resetForm()"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>

                <button type="submit" name="<?= $modeEdition ? 'UpdatePromotion' : 'InsertPromotion' ?>"
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
            Associer Promotion Livre
        </h2>

        <form id="associationForm" method="post" class="space-y-6">
            <input type="hidden" name="idLivre" value="<?= htmlspecialchars($associerPromoLivre['idLivre'] ?? '') ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="idLivre">ID du livre</label>
                <input type="text" value="<?= htmlspecialchars($associerPromoLivre['idLivre'] ?? '') ?>" readonly
                       class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nomLivre">Nom du livre</label>
                <input type="text" value="<?= htmlspecialchars($associerPromoLivre['nomLivre'] ?? '') ?>" readonly
                       class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="nomPromotion">
                    <i class="fas fa-heading mr-2 text-indigo-600"></i>Nom de la promotion
                </label>
                <select name="nomPromotion" id="nomPromotion"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        required>
                    <option value="">-- Sélectionnez une promotion --</option>
                    <?php foreach ($lesPromotions as $unePromotion): ?>
                        <option value="<?= htmlspecialchars($unePromotion['idPromotion']) ?>">
                            <?= htmlspecialchars($unePromotion['nomPromotion']) ?>
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
        document.getElementById('promoForm').reset();
        document.getElementById('btnEdit').classList.add('hidden');
    });

    function resetForm() {
        if (confirm("Annuler les modifications ?")) {
            window.location.href = 'index.php?page=7';
        }
    }
</script>

<?php
require_once("includes/footer.php");