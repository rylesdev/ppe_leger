<?php
$titrePage = "Abonnement";
require_once("includes/header.php");

require_once("controleur/controleur.class.php");
$unControleur = new Controleur();
$idUser = $_SESSION['idUser'];
?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/css/style.css">

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-blue-50 rounded-xl shadow-md overflow-hidden p-6 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-blue-800 mb-4">Abonnement Premium</h2>
                <div class="text-xl font-semibold text-blue-700 mb-6">
                    <?php echo "Nombre de points : " . $unControleur->selectPointAbonnement($idUser)['pointAbonnement']; ?>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-500 rounded-lg p-4 mb-6 text-left">
                    <p class="text-blue-800 font-medium mb-3">L'abonnement vous donne des avantages comme :</p>
                    <ul class="list-disc pl-5 space-y-2 text-blue-700">
                        <li>Des livres offerts après avoir acheté des livres (chance : 1/5)</li>
                        <li>Des points de fidélité à l'achat de chaque livre qui vous permettront d'obtenir des livres gratuitement</li>
                    </ul>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full rounded-lg overflow-hidden shadow">
                    <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-6 py-3 text-center font-semibold">Durée</th>
                        <th class="px-6 py-3 text-center font-semibold">Prix</th>
                        <th class="px-6 py-3 text-center font-semibold">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-blue-50 even:bg-blue-100">
                        <td class="px-6 py-4 text-center text-blue-800 font-medium">1 mois</td>
                        <td class="px-6 py-4 text-center text-blue-800">10€</td>
                        <td class="px-6 py-4 text-center">
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_MONTH">
                                <button type="submit" name="Abonnement1m" class="bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-md transition duration-200 transform hover:scale-105">
                                    S'abonner
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr class="bg-blue-50 even:bg-blue-100">
                        <td class="px-6 py-4 text-center text-blue-800 font-medium">3 mois</td>
                        <td class="px-6 py-4 text-center text-blue-800">25€</td>
                        <td class="px-6 py-4 text-center">
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_3_MONTHS">
                                <button type="submit" name="Abonnement3m" class="bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-md transition duration-200 transform hover:scale-105">
                                    S'abonner
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr class="bg-blue-50 even:bg-blue-100">
                        <td class="px-6 py-4 text-center text-blue-800 font-medium">1 an</td>
                        <td class="px-6 py-4 text-center text-blue-800">80€</td>
                        <td class="px-6 py-4 text-center">
                            <form method="post" class="flex justify-center">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_YEAR">
                                <button type="submit" name="Abonnement1a" class="bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-md transition duration-200 transform hover:scale-105">
                                    S'abonner
                                </button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <?php
            $dateAbonnement = $unControleur->selectDateAbonnement($idUser);
            if ($dateAbonnement !== null && $dateAbonnement['jourRestant'] > 0) {
                echo '<div class="text-center mt-8">
                    <form method="post">
                        <button type="submit" name="ResilierAbonnement" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 transform hover:scale-105">
                            Résilier l\'abonnement
                        </button>
                    </form>
                  </div>';
            }
            ?>
        </div>
    </div>

<?php
require_once("includes/footer.php");
?>