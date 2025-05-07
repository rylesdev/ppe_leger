<div class="max-w-md mx-auto my-10 p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-blue-900 text-center mb-6">Connexion</h2>

    <form method="post" class="space-y-6">
        <div>
            <label for="emailUser" class="block text-sm font-medium text-gray-700 mb-1">
                Adresse Email <span class="text-red-500">*</span>
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

        <div class="flex justify-between items-center pt-4">
            <button type="reset" name="Annuler"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Annuler
            </button>
            <button type="submit" name="Connexion"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Connexion
            </button>
        </div>

        <div class="text-center text-sm text-gray-500">
            <small><span class="text-red-500">*</span> Champs obligatoires</small>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Mot de passe oublié ?</a>
    </div>
</div>