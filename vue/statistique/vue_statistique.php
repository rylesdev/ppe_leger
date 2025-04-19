<?php
$titrePage = "Statistiques";
require_once("includes/header.php");
?>

    <div class="container">
        <table class="table">
            <thead class="table-success">
            <tr>
                <th scope="col">Nombre de commande(s) 'en attente'</th>
                <th scope="col">Liste des meilleures ventes (du meilleur au pire)</th>
                <th scope="col">Liste des livres en rupture de stock (- de 5 exemplaires)</th>
                <th scope="col">Meilleurs avis (livres les mieux notés)</th>
                <th scope="col">Nombre de livres achetés (par l'utilisateur)</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $idUser = $_SESSION['idUser'];

            $vCommandesEnAttente = $unControleur->viewSelectCommandesEnAttente();
            $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
            $vLivresEnStock = $unControleur->viewSelectLivresEnStock();
            $vMeilleursAvis = $unControleur->viewMeilleursAvis();
            $vNbLivreAcheteUser = $unControleur->viewNbLivreAcheteUser();

            echo "<tr>";
            echo "<td>" . $vCommandesEnAttente[0]['nbCommandeEnAttente'] . "</td>";

            echo "<td>";
            for ($i = 0; $i < count($vMeilleuresVentes); $i++) {
                echo $vMeilleuresVentes[$i]['nomLivre'] . " (Vendus: " . $vMeilleuresVentes[$i]['totalVendu'] . ")<br>";
            }
            echo "</td>";

            echo "<td>";
            for ($i = 0; $i < count($vLivresEnStock); $i++) {
                echo $vLivresEnStock[$i]['nomLivre'] . " (Exemplaires restants: " . $vLivresEnStock[$i]['exemplaireLivre'] . ")<br>";
            }
            echo "</td>";

            echo "<td>";
            for ($i = 0; $i < count($vMeilleursAvis); $i++) {
                echo $vMeilleursAvis[$i]['nomLivre'] . " (Note: " . number_format($vMeilleursAvis[$i]['moyenneNote'], 1) . "/5)<br>";
            }
            echo "</td>";

            echo "<td>";
            for ($i = 0; $i < count($vNbLivreAcheteUser); $i++) {
                echo "Email : " . $vNbLivreAcheteUser[$i]['emailUser'] . " (Livres achetés: " . $vNbLivreAcheteUser[$i]['nbLivreAchete'] . ")<br>";
            }
            echo "</td>";

            echo "</tr>";
            ?>
            </tbody>
        </table>
    </div>

<?php
require_once('includes/footer.php');
?>