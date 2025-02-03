<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
    Abonnement
</header>
<?php
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();

$idUser = $_SESSION['idUser'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table-success {
            background-color: #2E6E49 !important;
            color: white !important;
        }

        .footer {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
        }

        .footer-banner-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
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
<footer class="footer">
    <p>&copy; 2025 Librairie en ligne - Tous droits réservés</p>
</footer>
</body>
</html>