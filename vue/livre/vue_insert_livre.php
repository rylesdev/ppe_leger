<?php
$titrePage = "Ajout d'un livre";
require_once("includes/header.php");

$categorie = $unControleur->executerRequete("SELECT nomCategorie FROM categorie");
$maisonEdition = $unControleur->executerRequete("SELECT nomMaisonEdition FROM maisonEdition");
$promotion = $unControleur->executerRequete("SELECT nomPromotion FROM promotion");
?>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Alpine.js CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#1A365D',
                    primaryDark: '#0f2340',
                }
            }
        }
    }
</script>

<div class="container mx-auto p-4" x-data="{
    formData: {
        idLivre: '<?= ($leLivre != null) ? $leLivre['idLivre'] : '' ?>',
        nomLivre: '<?= ($leLivre != null) ? addslashes($leLivre['nomLivre']) : '' ?>',
        auteurLivre: '<?= ($leLivre != null) ? addslashes($leLivre['auteurLivre']) : '' ?>',
        imageLivre: '<?= ($leLivre != null) ? addslashes($leLivre['imageLivre']) : '' ?>',
        exemplaireLivre: '<?= ($leLivre != null) ? $leLivre['exemplaireLivre'] : '' ?>',
        prixLivre: '<?= ($leLivre != null) ? $leLivre['prixLivre'] : '' ?>',
        nomCategorie: '<?= ($leLivre != null) ? addslashes($leLivre['nomCategorie']) : '' ?>',
        nomMaisonEdition: '<?= ($leLivre != null) ? addslashes($leLivre['nomMaisonEdition']) : '' ?>',
        nomPromotion: '<?= ($leLivre != null) ? addslashes($leLivre['nomPromotion'] ?? '') : '' ?>'
    },
    imagePreview: '<?= ($leLivre != null) ? "images/livres/" . addslashes($leLivre['imageLivre']) : '' ?>',
    updatePreview() {
        if (this.formData.imageLivre) {
            this.imagePreview = 'images/livres/' + this.formData.imageLivre;
        }
    },
    isFormValid() {
        return this.formData.nomLivre &&
               this.formData.auteurLivre &&
               this.formData.imageLivre &&
               this.formData.exemplaireLivre > 0 &&
               this.formData.prixLivre > 0 &&
               this.formData.nomCategorie &&
               this.formData.nomMaisonEdition &&
               this.formData.nomPromotion;
    }
}">

    <h1 class="text-2xl font-bold text-primary text-center mb-6">
        <?= ($leLivre != null) ? "Modification d'un livre" : "Ajout d'un livre" ?>
    </h1>

    <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Prévisualisation de l'image -->
            <div class="md:w-1/3 flex flex-col items-center">
                <h3 class="text-lg font-semibold text-primary mb-4">Prévisualisation</h3>
                <div class="border border-gray-300 rounded-lg p-4 flex items-center justify-center w-full h-64">
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="max-h-full max-w-full object-contain" alt="Prévisualisation du livre">
                    </template>
                    <template x-if="!imagePreview">
                        <div class="text-gray-400 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>Aucune image sélectionnée</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="md:w-2/3">
                <form method="post" class="space-y-4" @submit="if(!isFormValid()) { $event.preventDefault(); alert('Veuillez remplir tous les champs obligatoires.'); }">
                    <?php if ($leLivre != null): ?>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="idLivre">ID du livre</label>
                            <input type="text" name="idLivre" id="idLivre" x-model="formData.idLivre" readonly
                                   class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomLivre">
                            Nom du livre <span class="text-primary">*</span>
                        </label>
                        <input type="text" name="nomLivre" id="nomLivre" x-model="formData.nomLivre" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="auteurLivre">
                            Auteur du livre <span class="text-primary">*</span>
                        </label>
                        <input type="text" name="auteurLivre" id="auteurLivre" x-model="formData.auteurLivre" required
                               class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="imageLivre">
                            Nom du fichier image <span class="text-primary">*</span>
                        </label>
                        <div class="flex">
                            <input type="text" name="imageLivre" id="imageLivre" x-model="formData.imageLivre"
                                   @input="updatePreview()" required
                                   class="appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                            <button type="button" @click="updatePreview()"
                                    class="bg-primary text-white px-4 py-2 rounded-r hover:bg-primaryDark transition">
                                Aperçu
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Le fichier doit être dans le dossier images/livres/</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="exemplaireLivre">
                                Nombre d'exemplaires <span class="text-primary">*</span>
                            </label>
                            <input type="number" name="exemplaireLivre" id="exemplaireLivre"
                                   x-model="formData.exemplaireLivre" min="0" required
                                   class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="prixLivre">
                                Prix du livre (€) <span class="text-primary">*</span>
                            </label>
                            <input type="number" name="prixLivre" id="prixLivre"
                                   x-model="formData.prixLivre" min="0" step="0.01" required
                                   class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomCategorie">
                            Catégorie <span class="text-primary">*</span>
                        </label>
                        <select name="nomCategorie" id="nomCategorie" x-model="formData.nomCategorie" required
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                            <option value="">-- Sélectionnez une catégorie --</option>
                            <?php foreach ($categorie as $uneCategorie): ?>
                                <option value="<?= $uneCategorie ?>"><?= $uneCategorie ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomMaisonEdition">
                            Maison d'édition <span class="text-primary">*</span>
                        </label>
                        <select name="nomMaisonEdition" id="nomMaisonEdition" x-model="formData.nomMaisonEdition" required
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                            <option value="">-- Sélectionnez une maison d'édition --</option>
                            <?php foreach ($maisonEdition as $uneMaisonEdition): ?>
                                <option value="<?= $uneMaisonEdition ?>"><?= $uneMaisonEdition ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nomPromotion">
                            Promotion <span class="text-primary">*</span>
                        </label>
                        <select name="nomPromotion" id="nomPromotion" x-model="formData.nomPromotion" required
                                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-primary">
                            <option value="">-- Sélectionnez une promotion --</option>
                            <?php foreach ($promotion as $unePromotion): ?>
                                <option value="<?= $unePromotion ?>"><?= $unePromotion ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-primaryDark">
                            <?= ($leLivre != null) ? "Modifier" : "Ajouter" ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
