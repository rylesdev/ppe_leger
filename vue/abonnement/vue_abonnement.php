<?php
$titrePage = "Abonnement";
require_once("includes/header.php");

require_once("controleur/controleur.class.php");
$unControleur = new Controleur();
$idUser = $_SESSION['idUser'];
?>

    <link rel="stylesheet" href="includes/css/vue_abonnement.css">

<body>
<div class="container">
    <?php echo "<h3>Nombre de points : " . $unControleur->selectPointAbonnement($idUser)['pointAbonnement'] . "</h3>" ?>
    L'abonnement vous donne des avantages comme : <br>
    - des livres offert après avoir acheté des livres (chance : 1/5). <br>
    - des points de fidélité à l'achat de chaque livre qui vous permettront d'obtenir des livres gratuitement.
    <table>
        <tr>
            <th>Durée</th>
            <th>Prix</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>1 mois</td>
            <td>10€</td>
            <td>
                <form method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_MONTH">
                    <input type="submit" name="Abonnement1m" value="S'abonner" class="btn btn-primary">
                </form>
            </td>
        </tr>
        <tr>
            <td>3 mois</td>
            <td>25€</td>
            <td>
                <form method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_3_MONTHS">
                    <input type="submit" name="Abonnement3m" value="S'abonner" class="btn btn-primary">
                </form>
            </td>
        </tr>
        <tr>
            <td>1 an</td>
            <td>80€</td>
            <td>
                <form method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="YOUR_PAYPAL_BUTTON_ID_1_YEAR">
                    <input type="submit" name="Abonnement1a" value="S'abonner" class="btn btn-primary">
                </form>
            </td>
        </tr>
    </table>
</div>

<?php
require_once("includes/footer.php");
?>
</body>
</html>