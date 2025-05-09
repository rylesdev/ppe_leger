<?php
session_start();

// Vérifier si l'utilisateur est connecté (idUser existe) avant d'appeler selectAdminPrincipal
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();
$isAdmin = 0; // Valeur par défaut

if (isset($_SESSION['idUser'])) {
    $resultAdminPrincipal = $unControleur->selectAdminPrincipal($_SESSION['idUser']);
    if (!empty($resultAdminPrincipal) && isset($resultAdminPrincipal[0][0])) {
        $isAdmin = $resultAdminPrincipal[0][0];
    }
}

// Gestion de la redirection avant tout output HTML
if (isset($_GET['page']) && $_GET['page'] == 11) {
    if (isset($_SESSION['emailUser']) && $_SESSION['emailUser'] != NULL) {
        if (isset($_POST['ConfirmerDeconnexion'])) {
            session_unset();
            session_destroy();
            header("Location: index.php?page=12");
            exit();
        }
        // Si déconnexion n'est pas confirmée, continuer avec l'affichage normal
    } else {
        // Si l'utilisateur n'est pas connecté, rediriger directement
        header("Location: index.php?page=12");
        exit();
    }
}

// Début de l'output HTML maintenant qu'on a géré les redirections
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>PPE Book'In</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body class="bg-gray-100">
<header class="bg-primary-blue text-white text-center py-4 shadow-md">
    <h1 class="text-3xl font-bold">Book'In</h1>
    <div class="mt-2">
        <img src="images/logo.png" alt="Book'In Logo" class="inline-block h-16 w-16">
        <?php
        if ($isAdmin == 1) {
            echo '<p class="text-sm mt-2">Mode Admin</p>';
        }
        ?>
    </div>
</header>

<!-- Navigation Bar -->
<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <a href="index.php?page=1" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Accueil
                    </a>
                    <a href="index.php?page=2" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                        <i class="fas fa-book mr-2"></i> Livres
                    </a>
                    <?php if (empty($isAdmin) || $isAdmin == 0): ?>
                        <a href="index.php?page=3" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i> Panier
                        </a>
                        <a href="index.php?page=4" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-box mr-2"></i> Commande
                        </a>
                        <a href="index.php?page=5" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-ticket-alt mr-2"></i> Abonnement
                        </a>
                        <a href="index.php?page=6" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                    <?php endif; ?>
                    <?php if ($isAdmin == 1): ?>
                        <a href="index.php?page=9" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-list mr-2"></i> Catégorie
                        </a>
                        <a href="index.php?page=8" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h2M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Maison d'Édition
                        </a>
                        <a href="index.php?page=7" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-tags mr-2"></i> Promotion
                        </a>
                        <a href="index.php?page=10" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques
                        </a>
                    <?php endif; ?>
                    <a href="index.php?page=11" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Authentification
                    </a>

                </div>
            </div>
            <!-- Hamburger Menu Button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button id="menu-toggle" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-white hover:bg-primary-blue focus:outline-none">
                    <span class="sr-only">Ouvrir le menu</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden sm:hidden bg-white shadow-md">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="index.php?page=1" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                <i class="fas fa-home mr-2"></i> Accueil
            </a>
            <a href="index.php?page=2" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                <i class="fas fa-book mr-2"></i> Livres
            </a>
            <?php if (empty($isAdmin) || $isAdmin == 0): ?>
                <a href="index.php?page=3" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-shopping-cart mr-2"></i> Panier
                </a>
                <a href="index.php?page=4" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-box mr-2"></i> Commande
                </a>
                <a href="index.php?page=5" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-ticket-alt mr-2"></i> Abonnement
                </a>
                <a href="index.php?page=6" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>
            <?php endif; ?>
            <?php if ($isAdmin == 1): ?>
                <a href="index.php?page=9" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-list mr-2"></i> Catégorie
                </a>
                <a href="index.php?page=8" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-warehouse mr-2"></i> Maison d'Édition
                </a>
                <a href="index.php?page=7" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-tags mr-2"></i> Promotion
                </a>
                <a href="index.php?page=10" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                </a>
            <?php endif; ?>
            <a href="index.php?page=12" class="flex items-center px-3 py-2 text-gray-700 hover:bg-primary-blue hover:text-white rounded-md">
                <i class="fas fa-sign-out-alt mr-2"></i> Authentification
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    switch ($page) {
        case 1: require_once("controleur/home.php"); break;
        case 2: require_once("controleur/c_livre.php"); break;
        case 3: require_once("controleur/c_panier.php"); break;
        case 4: require_once("controleur/c_commande.php"); break;
        case 5: require_once("controleur/c_abonnement.php"); break;
        case 6: require_once("controleur/c_user.php"); break;
        case 7: require_once("controleur/c_promotion.php"); break;
        case 8: require_once("controleur/c_maisonEdition.php"); break;
        case 9: require_once("controleur/c_categorie.php"); break;
        case 10: require_once("controleur/c_statistique.php"); break;
        case 11:
            if (isset($_SESSION['emailUser']) && $_SESSION['emailUser'] != NULL) {
                ?>
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto mt-8">
                    <h2 class="text-2xl font-bold text-primary-blue text-center mb-6">Déconnexion</h2>
                    <p class="text-gray-700 text-center mb-6">Voulez-vous vraiment vous déconnecter ?</p>
                    <form method="post" class="flex justify-center space-x-4">
                        <button type="submit" name="ConfirmerDeconnexion" class="bg-accent-red hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Confirmer
                        </button>
                        <a href="index.php?page=1" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-xl shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </form>
                </div>
                <?php
            }
            break;
        case 12:
            require_once("controleur/c_authentification.php");
            break;
    }
    ?>
</main>

<!-- JavaScript for Hamburger Menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>
</body>
</html>