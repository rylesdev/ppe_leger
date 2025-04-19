<?php
$titrePage = "Stockage des livres";
require_once("includes/header.php");
?>

<div class="container">
    <form method="post">
        <table>
            <tr>
                <td> Nom du livre </td>
                <td> <input type="text" name="nomLivre"
                            value="<?= ($leLivre!=null)? $leLivre['nomLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td> Nombre d'exemplaire </td>
                <td> <input type="text" name="exemplaireLivre"
                            value="<?= ($leLivre!=null)? $leLivre['exemplaireLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td> <input type="reset" name="Annuler" value="Annuler" class="table-success"> </td>
                <td> <input type="submit" name="ValiderStockage" value="Valider" class="table-success"></td>
            </tr>
        </table>
        <?= ($leLivre!=null) ? '<input type="hidden" name="idLivre" value="'.$leLivre['idLivre'].'">' : '' ?>
    </form>
</div>
<br>