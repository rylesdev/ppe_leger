<h3> Liste des livres (<?= count($lesLivres) ?>) </h3>

<form method="post">
Filtrer par : <input type="text" name="filtre">
<input type="submit" name="Filtrer" value="Filtrer" class="table-success">
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
        <th scope="col">Prix</th>
        <th scope="col">Opérations</th>
    </tr>
  </thead>
<tbody>

<?php
$idUser = $_SESSION['idUser'];

	if (isset($lesLivres)){
		foreach ($lesLivres as $unLivre) {
			echo "<tr>";
            echo "<td><img src='images/livres/".$unLivre['imageLivre']."' height='150' width='100'></td>";
            echo "<td>".$unLivre['nomLivre']."</td>";
            echo "<td>".$unLivre['nomCategorie']."</td>";
            echo "<td>".$unLivre['auteurLivre']."</td>";
            echo "<td>".$unLivre['exemplaireLivre']."</td>";
            echo "<td>".$unLivre['prixLivre']."€</td>";


            if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="admin"){
			echo "<td>";
			echo "<a href='index.php?page=2&action=sup&idLivre=".$unLivre['idLivre']."'>"."<img src='images/supprimer.png' heigth='30' width='30'> </a>";
			echo "<a href='index.php?page=2&action=edit&idLivre=".$unLivre['idLivre']."'>"."<img src='images/editer.png' heigth='30' width='30'> </a>";
			echo "</td>";
            }

            if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="client") {
                echo "<td>";

?>
<form method="post">
    <table>
        <tr>
            <td> Entrer la quantité :</td> <br>
            <td> <input type="text" name="insertQuantiteLivre" style="width:30px"></td>
        </tr>
        <tr>
            <td> <input type="submit" name="QuantiteLivre" value="Confirmer la quantité" class="table-success"></td>
        </tr>
    </table>
</form>
<?php
                echo "<a href='index.php?page=2&action=acheter&idLivre=".$unLivre['idLivre']."&idUser=".$idUser."'>"."Ajouter au panier  <img src='images/acheter.png' heigth='30' width='30' padding-left:'50'> </a>";
                echo "</td>";
            }
            echo "</tr>";
        }
    }
?>


</tbody>
</table>