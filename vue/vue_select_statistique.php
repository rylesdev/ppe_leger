<form method="post">
    Filtrer par : <input type="text" name="filtre">
    <input type="submit" name="Filtrer" value="Filtrer" class="table-success">
</form>
<br>

<table class="table" style="margin-left: 50px ; margin-right: 50px">
    <thead class="table-success">
    <tr>
        <th scope="col">Nombre de commande(s) 'en attente'</th>
        <th scope="col">Liste des meilleures ventes (du meilleur au pire)</th>
        <th scope="col">Liste des livres en rupture de stock (- de 5 exemplaires)</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $idUser = $_SESSION['idUser'];

    $vCommandesEnAttente = $unControleur->viewSelectCommandesEnAttente();
    $vMeilleuresVentes = $unControleur->viewSelectMeilleuresVentes();
    $vLivresEnStock = $unControleur->viewSelectLivresEnStock();

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

    echo "</tr>";
    ?>

    </tbody>
</table>
