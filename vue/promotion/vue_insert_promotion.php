<?php
$titrePage = "Ajout d'une promotion";
require_once("includes/header.php");

// Initialisation de $laPromotion si non défini
$laPromotion = $laPromotion ?? null;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

<div class="container mx-auto px-4 py-6">
    <h3 class="text-2xl font-bold text-center mb-6 text-indigo-900">
        <?= ($laPromotion != null) ? "Modifier une promotion" : "Ajouter une promotion" ?>
    </h3>

    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <form method="post" class="space-y-6" id="promotionForm">
            <?php if ($laPromotion != null): ?>
                <input type="hidden" name="idPromotion" value="<?= htmlspecialchars($laPromotion['idPromotion'] ?? '') ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="idPromotion">ID de la promotion</label>
                    <input type="text" value="<?= htmlspecialchars($laPromotion['idPromotion'] ?? '') ?>" readonly
                           class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nomLivre">Nom du livre <span class="text-red-500">*</span></label>
                <input type="text" name="nomLivre" id="nomLivre" value="<?= htmlspecialchars($laPromotion['nomLivre'] ?? '') ?>" required
                       class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nomPromotion">
                        Nom de la promotion <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomPromotion" id="nomPromotion" value="<?= htmlspecialchars($laPromotion['nomPromotion'] ?? '') ?>" required
                           class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dateDebutPromotion">
                        Date de début <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="dateDebutPromotion" id="dateDebutPromotion" value="<?= htmlspecialchars($laPromotion['dateDebutPromotion'] ?? '') ?>" required
                           class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dateFinPromotion">
                        Date de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="dateFinPromotion" id="dateFinPromotion" value="<?= htmlspecialchars($laPromotion['dateFinPromotion'] ?? '') ?>" required
                           class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="reductionPromotion">
                        Réduction (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="reductionPromotion" id="reductionPromotion" value="<?= htmlspecialchars($laPromotion['reductionPromotion'] ?? '') ?>" required
                           class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-900">
                </div>
            </div>

            <div class="flex justify-between items-center pt-4">
                <button type="button" onclick="confirmReset()" name="AnnulerPromotion"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Annuler
                </button>
                <?php if ($laPromotion != null): ?>
                    <button type="submit" name="UpdatePromotion"
                            class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Modifier
                    </button>
                <?php else: ?>
                    <button type="submit" name="InsertPromotion"
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

<script>
    function confirmReset() {
        if (confirm("Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les modifications seront perdues.")) {
            resetForm();
        }
    }

    function resetForm() {
        if (<?= ($laPromotion != null) ? 'true' : 'false' ?>) {
            // Si en mode édition, rediriger vers la page sans paramètre d'édition
            window.location.href = window.location.pathname + '?page=7';
        } else {
            // Si en mode ajout, réinitialiser le formulaire
            const form = document.getElementById('promotionForm');

            // Réinitialiser les champs input
            const inputs = form.querySelectorAll('input[type="text"], input[type="number"], input[type="date"]');
            inputs.forEach(input => {
                input.value = '';
            });

            // Remettre le focus sur le premier champ
            document.getElementById('nomPromotion').focus();
        }
    }
</script>

<?php
require_once("includes/footer.php");
?>
