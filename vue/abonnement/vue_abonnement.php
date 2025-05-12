<?php
$titrePage = "Abonnement";
require_once("includes/header.php");

require_once("controleur/controleur.class.php");
$unControleur = new Controleur();
$idUser = $_SESSION['idUser'];
$pointsAbonnement = $unControleur->selectPointAbonnement($idUser)['pointAbonnement'];
$dateAbonnement = $unControleur->selectDateAbonnement($idUser);
$estAbonne = $dateAbonnement !== null && $dateAbonnement[0][0] > 0;
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="includes/css/style.css">

<div class="bg-gradient-to-b from-blue-50 to-indigo-100 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-10 rounded-full -ml-24 -mb-24"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-6 md:mb-0">
                        <h1 class="text-4xl font-bold mb-2">Abonnement Premium</h1>
                        <p class="text-blue-100 text-lg">Profitez d'avantages exclusifs en devenant membre Premium</p>
                    </div>

                    <?php if($estAbonne): ?>
                        <div class="bg-blue-900 bg-opacity-50 p-4 rounded-lg flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div>
                                <p class="text-sm text-blue-200">Abonnement actif</p>
                                <p class="font-bold"><?php echo $dateAbonnement[0][0]; ?> jours restants</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h2 class="text-2xl font-bold text-blue-800">Vos points</h2>
                            <p class="text-xl text-blue-600"><?php echo $pointsAbonnement; ?> points disponibles</p>
                        </div>
                    </div>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition transform hover:scale-105 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        <span>Obtenir plus de points</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12">
            <div class="p-8">
                <h2 class="text-3xl font-bold text-blue-800 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    Avantages Premium
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-xl p-6 flex">
                        <div class="bg-blue-100 rounded-lg p-4 mr-4 self-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-blue-800 mb-2">Livres offerts</h3>
                            <p class="text-blue-700">Un livre gratuit peut vous être offert après chaque achat avec une chance sur cinq de l'obtenir!</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-6 flex">
                        <div class="bg-blue-100 rounded-lg p-4 mr-4 self-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-blue-800 mb-2">Accès anticipé</h3>
                            <p class="text-blue-700">Accédez aux nouvelles sorties et aux événements exclusifs avant tout le monde!</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-6 flex">
                        <div class="bg-blue-100 rounded-lg p-4 mr-4 self-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-blue-800 mb-2">Points de fidélité</h3>
                            <p class="text-blue-700">Gagnez des points à chaque achat que vous pourrez échanger contre des livres gratuits!</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-6 flex">
                        <div class="bg-blue-100 rounded-lg p-4 mr-4 self-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-blue-800 mb-2">Support prioritaire</h3>
                            <p class="text-blue-700">Bénéficiez d'un support client dédié et prioritaire pour toutes vos questions!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-3xl font-bold text-blue-800 mb-8 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Choisissez votre formule
                </h2>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-b from-blue-50 to-blue-100 rounded-xl shadow-md overflow-hidden transition transform hover:scale-105 hover:shadow-lg">
                        <div class="bg-blue-600 py-4 px-6 text-white">
                            <h3 class="text-xl font-bold">Abonnement Découverte</h3>
                            <p class="text-blue-100">1 mois</p>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl font-bold text-blue-800">10€</span>
                            </div>
                            <ul class="mb-6 space-y-3">
                                <li class="flex items-center text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Livres gratuits (1/5)
                                </li>
                                <li class="flex items-center text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Points de fidélité
                                </li>
                            </ul>
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_MONTH">
                                <button type="submit" name="Abonnement1m" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    S'abonner
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gradient-to-b from-indigo-50 to-indigo-100 rounded-xl shadow-md overflow-hidden relative transition transform hover:scale-105 hover:shadow-lg">
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 text-xs font-bold py-1 px-3 rounded-bl-lg">
                            POPULAIRE
                        </div>
                        <div class="bg-indigo-600 py-4 px-6 text-white">
                            <h3 class="text-xl font-bold">Abonnement Confort</h3>
                            <p class="text-indigo-100">3 mois</p>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl font-bold text-indigo-800">25€</span>
                                <span class="text-indigo-600 mt-1 ml-2">(8€/mois)</span>
                            </div>
                            <ul class="mb-6 space-y-3">
                                <li class="flex items-center text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Livres gratuits (1/5)
                                </li>
                                <li class="flex items-center text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Points de fidélité
                                </li>
                            </ul>
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_3_MONTHS">
                                <button type="submit" name="Abonnement3m" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    S'abonner
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gradient-to-b from-purple-50 to-purple-100 rounded-xl shadow-md overflow-hidden transition transform hover:scale-105 hover:shadow-lg">
                        <div class="bg-purple-600 py-4 px-6 text-white">
                            <h3 class="text-xl font-bold">Abonnement Premium</h3>
                            <p class="text-purple-100">1 an</p>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl font-bold text-purple-800">80€</span>
                                <span class="text-purple-600 mt-1 ml-2">(6,7€/mois)</span>
                            </div>
                            <ul class="mb-6 space-y-3">
                                <li class="flex items-center text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Livres gratuits (1/5)
                                </li>
                                <li class="flex items-center text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Points de fidélité
                                </li>
                            </ul>
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_YEAR">
                                <button type="submit" name="Abonnement1a" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-md transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    S'abonner
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if ($estAbonne): ?>
                    <div class="mt-12 text-center">
                        <form method="post">
                            <button type="submit" name="ResilierAbonnement" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-8 rounded-lg transition transform hover:scale-105 flex items-center mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Résilier mon abonnement
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once("includes/footer.php");
?>
