<h3> Liste des livres (<?= count($lesLivres) ?>) </h3>

<form method="post">
    Filtrer par : <input type="text" name="filtre">
    <input type="submit" name="FiltrerStockage" value="Filtrer" class="table-success">
</form>
<br>

<table class="table" style="margin-left: 50px ; margin-right: 50px">
    <thead class="table-success">
    <tr>
        <th scope="col">Image</th>
        <th scope="col">Nom</th>
        <th scope="col">Categorie</th>
        <th scope="col">Auteur</th>
        <th scope="col">Nombre Exemplaires</th>
    </tr>
    </thead>
    <tbody>

    <?php
    if (isset($lesLivres)){
        $i = 1;
        foreach ($lesLivres as $unLivre) {
            echo "<tr>";
            echo "<td><img src='images/livres/".$unLivre['imageLivre']."' height='150' width='100'></td>";
            echo "<td>".$unLivre['nomLivre']."</td>";
            echo "<td>".$unLivre['categorieLivre']."</td>";
            echo "<td>".$unLivre['auteurLivre']."</td>";
            echo "<td>".$unLivre['exemplaireLivre']."</td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>

<?php
require_once('includes/footer.php');
?>