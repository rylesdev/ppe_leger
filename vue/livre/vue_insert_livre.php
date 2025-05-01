<?php
$titrePage = "Ajout d'un livre";
require_once("includes/header.php");
?>

    <form method="post">
        <table>
            <?php if ($leLivre != null): ?>
                <tr>
                    <td>ID du livre</td>
                    <td><input type="text" name="idLivre" value="<?= $leLivre['idLivre'] ?>" readonly></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td>Nom du livre</td>
                <td><input type="text" name="nomLivre" value="<?= ($leLivre != null) ? $leLivre['nomLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Auteur du livre</td>
                <td><input type="text" name="auteurLivre" value="<?= ($leLivre != null) ? $leLivre['auteurLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Lien de l'image du livre</td>
                <td><input type="text" name="imageLivre" value="<?= ($leLivre != null) ? $leLivre['imageLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Nombre d'exemplaires</td>
                <td><input type="number" name="exemplaireLivre" value="<?= ($leLivre != null) ? $leLivre['exemplaireLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Prix du livre (utiliser . au lieu de ,)</td>
                <td><input type="text" name="prixLivre" value="<?= ($leLivre != null) ? $leLivre['prixLivre'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Catégorie</td>
                <td><input type="text" name="nomCategorie" value="<?= ($leLivre != null) ? $leLivre['nomCategorie'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Maison d'édition</td>
                <td><input type="text" name="nomMaisonEdition" value="<?= ($leLivre != null) ? $leLivre['nomMaisonEdition'] : '' ?>"></td>
            </tr>
            <tr>
                <td>Promotion</td>
                <td>
                    <input type="text" name="nomPromotion"
                           value="<?= ($leLivre != null) ? $leLivre['nomPromotion'] : 'Aucune Promotion' ?>">
                </td>
            </tr>
            <tr>
                <td><input type="reset" name="Annuler" value="Annuler" class="table-success"></td>
                <td>
                    <?php if ($leLivre != null): ?>
                        <input type="submit" name="Modifier" value="Modifier" class="table-success">
                    <?php else: ?>
                        <input type="submit" name="ValiderInsert" value="Valider" class="table-success">
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>

    <br><br>

<?php
require_once("includes/footer.php");
?>