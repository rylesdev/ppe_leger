<?php
$titrePage = "Informations de l'utilisateur";
require_once("includes/header.php");
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="includes/css/style.css">

    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Contenu principal : informations du profil -->
        <div class="bg-white rounded-xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl mb-8">
            <?php
            if (isset($idUser)) {
                $userParticulier = $unControleur->selectParticulier($idUser);
                $userEntreprise = $unControleur->selectEntreprise($idUser);

                if ($userParticulier) {
                    echo '<div class="text-center mb-8">';
                    echo '<div class="inline-block p-4 rounded-full bg-blue-100 text-blue-700 mb-4 transform transition-transform hover:scale-110">';
                    echo '<i class="fas fa-user-circle text-6xl"></i>';
                    echo '</div>';
                    echo '<h3 class="text-3xl font-bold text-blue-700 mb-2">Profil Particulier</h3>';
                    echo '</div>';

                    echo '<div class="grid md:grid-cols-2 gap-6">';
                    // Colonne 1
                    echo '<div class="space-y-4">';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][0]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">••••••••</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][2]) . '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Colonne 2
                    echo '<div class="space-y-4">';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][3]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][4]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][5]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userParticulier[0][6]) . '</div>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>'; // Fin du grid

                } else if ($userEntreprise) {
                    echo '<div class="text-center mb-8">';
                    echo '<div class="inline-block p-4 rounded-full bg-blue-100 text-blue-700 mb-4 transform transition-transform hover:scale-110">';
                    echo '<i class="fas fa-building text-6xl"></i>';
                    echo '</div>';
                    echo '<h3 class="text-3xl font-bold text-blue-700 mb-2">Profil Entreprise</h3>';
                    echo '</div>';

                    echo '<div class="grid md:grid-cols-2 gap-6">';
                    // Colonne 1
                    echo '<div class="space-y-4">';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userEntreprise[0][0]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">••••••••</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userEntreprise[0][2]) . '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Colonne 2
                    echo '<div class="space-y-4">';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">SIRET</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userEntreprise[0][3]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Raison sociale</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . htmlspecialchars($userEntreprise[0][4]) . '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label class="block text-sm font-medium text-gray-700 mb-2">Capital social</label>';
                    echo '<div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">' . number_format(htmlspecialchars($userEntreprise[0][5]), 2, ',', ' ') . ' €</div>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>'; // Fin du grid
                } else {
                    echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-lg">';
                    echo '<p class="font-medium">Aucun utilisateur sélectionné.</p>';
                    echo '</div>';
                }
            }
            ?>

            <div class="mt-8 flex justify-center space-x-6">
                <button id="showEditBtn" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Modifier mon profil
                </button>
                <button id="showDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i> Supprimer mon compte
                </button>
            </div>
        </div>

        <!-- Section Modification (cachée par défaut) -->
        <div id="editSection" class="bg-white rounded-xl shadow-lg p-8 mb-8 hidden transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-blue-700">Modifier mon profil</h3>
                <button id="closeEditBtn" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <?php
            if (isset($idUser)) {
                $userParticulier = $unControleur->selectParticulier($idUser);
                $userEntreprise = $unControleur->selectEntreprise($idUser);

                if ($userParticulier) {
                    ?>
                    <form method="post" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="emailUser" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="emailUser" name="emailUser" value="<?php echo htmlspecialchars($userParticulier[0][0]); ?>" required>
                                </div>
                                <div>
                                    <label for="mdpUser" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                                    <input type="password" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="mdpUser" name="mdpUser" value="<?php echo htmlspecialchars($userParticulier[0][1]); ?>" required>
                                </div>
                                <div>
                                    <label for="adresseUser" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="adresseUser" name="adresseUser" value="<?php echo htmlspecialchars($userParticulier[0][2]); ?>" required>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="nomUser" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="nomUser" name="nomUser" value="<?php echo htmlspecialchars($userParticulier[0][3]); ?>" required>
                                </div>
                                <div>
                                    <label for="prenomUser" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="prenomUser" name="prenomUser" value="<?php echo htmlspecialchars($userParticulier[0][4]); ?>" required>
                                </div>
                                <div>
                                    <label for="dateNaissanceUser" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                    <input type="date" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="dateNaissanceUser" name="dateNaissanceUser" value="<?php echo htmlspecialchars($userParticulier[0][5]); ?>" required>
                                </div>
                                <div>
                                    <label for="sexeUser" class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                                    <select class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                            id="sexeUser" name="sexeUser" required>
                                        <option value="M" <?php echo ($userParticulier[0][6] == 'M') ? 'selected' : ''; ?>>Masculin</option>
                                        <option value="F" <?php echo ($userParticulier[0][6] == 'F') ? 'selected' : ''; ?>>Féminin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-8">
                            <button type="submit" name="UpdateParticulier"
                                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center mx-auto">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                    <?php
                } elseif ($userEntreprise) {
                    ?>
                    <form method="post" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="emailUser" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="emailUser" name="emailUser" value="<?php echo htmlspecialchars($userEntreprise[0][0]); ?>" required>
                                </div>
                                <div>
                                    <label for="mdpUser" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                                    <input type="password" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="mdpUser" name="mdpUser" value="<?php echo htmlspecialchars($userEntreprise[0][1]); ?>" required>
                                </div>
                                <div>
                                    <label for="adresseUser" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="adresseUser" name="adresseUser" value="<?php echo htmlspecialchars($userEntreprise[0][2]); ?>" required>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="siretUser" class="block text-sm font-medium text-gray-700 mb-2">SIRET</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="siretUser" name="siretUser" value="<?php echo htmlspecialchars($userEntreprise[0][3]); ?>" required>
                                </div>
                                <div>
                                    <label for="raisonSocialeUser" class="block text-sm font-medium text-gray-700 mb-2">Raison sociale</label>
                                    <input type="text" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="raisonSocialeUser" name="raisonSocialeUser" value="<?php echo htmlspecialchars($userEntreprise[0][4]); ?>" required>
                                </div>
                                <div>
                                    <label for="capitalSocialUser" class="block text-sm font-medium text-gray-700 mb-2">Capital social</label>
                                    <input type="number" class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           id="capitalSocialUser" name="capitalSocialUser" step="0.01" min="0"
                                           value="<?php echo htmlspecialchars($userEntreprise[0][5]); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-8">
                            <button type="submit" name="UpdateEntreprise"
                                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center mx-auto">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Section Suppression (cachée par défaut) -->
        <div id="deleteSection" class="bg-white rounded-xl shadow-lg p-8 hidden transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-red-600">Supprimer mon compte</h3>
                <button id="closeDeleteBtn" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="text-center mb-6">
                <div class="inline-block p-4 rounded-full bg-red-100 text-red-600 mb-4 transform transition-transform hover:scale-110">
                    <i class="fas fa-exclamation-triangle text-6xl"></i>
                </div>
            </div>

            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 mb-6 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-medium">Attention : Cette action est irréversible !</p>
                        <p class="mt-2">La suppression de votre compte entraînera la perte définitive de :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Toutes vos informations personnelles</li>
                            <li>Votre historique de commandes</li>
                            <li>Vos préférences et paramètres</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="post" onsubmit="return confirm('Êtes-vous vraiment sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.');" class="mt-8">
                <div class="flex items-center mb-6">
                    <input type="checkbox" id="confirm-delete" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500" required>
                    <label for="confirm-delete" class="ml-3 text-gray-700 text-sm">
                        Je comprends que cette action est irréversible et je souhaite supprimer mon compte
                    </label>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" name="DeleteUser"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-xl shadow-md transition-all duration-300 flex items-center mx-auto">
                        <i class="fas fa-trash-alt mr-2"></i> Supprimer définitivement mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour la gestion des sections -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showEditBtn = document.getElementById('showEditBtn');
            const showDeleteBtn = document.getElementById('showDeleteBtn');
            const closeEditBtn = document.getElementById('closeEditBtn');
            const closeDeleteBtn = document.getElement bifId('closeDeleteBtn');

            const editSection = document.getElementById('editSection');
            const deleteSection = document.getElementById('deleteSection');

            showEditBtn.addEventListener('click', function() {
                editSection.classList.remove('hidden');
                deleteSection.classList.add('hidden');
                window.scrollTo({
                    top: editSection.offsetTop - 20,
                    behavior: 'smooth'
                });
            });

            showDeleteBtn.addEventListener('click', function() {
                deleteSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                window.scrollTo({
                    top: deleteSection.offsetTop - 20,
                    behavior: 'smooth'
                });
            });

            closeEditBtn.addEventListener('click', function() {
                editSection.classList.add('hidden');
            });

            closeDeleteBtn.addEventListener('click', function() {
                deleteSection.classList.add('hidden');
            });
        });
    </script>

<?php require_once("includes/footer.php"); ?>