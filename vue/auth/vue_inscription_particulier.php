<div class="auth-form-container">
    <div class="max-w-2xl mx-auto my-10 p-8 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-blue-900 text-center mb-6">Inscription Particulier</h2>

        <form method="post" id="particulierForm" class="space-y-5">
            <div>
                <label for="emailUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="emailUser" id="emailUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="mdpUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe <span class="text-red-500">*</span>
                </label>
                <input type="password" name="mdpUser" id="mdpUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="adresseUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Adresse Postale <span class="text-red-500">*</span>
                </label>
                <input type="text" name="adresseUser" id="adresseUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="nomUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Nom <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nomUser" id="nomUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="prenomUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Prénom <span class="text-red-500">*</span>
                </label>
                <input type="text" name="prenomUser" id="prenomUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="dateNaissanceUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Date de Naissance <span class="text-red-500">*</span>
                </label>
                <input type="date" name="dateNaissanceUser" id="dateNaissanceUser" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="sexeUser" class="block text-sm font-medium text-gray-700 mb-1">
                    Sexe <span class="text-red-500">*</span>
                </label>
                <select name="sexeUser" id="sexeUser" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Sélectionnez --</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="reset" name="Annuler"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Annuler
                </button>
                <button type="submit" name="InscriptionParticulier"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Inscription
                </button>
            </div>

            <div class="text-center text-sm text-gray-500">
                <small><span class="text-red-500">*</span> Champs obligatoires</small>
            </div>
        </form>
    </div>
</div>