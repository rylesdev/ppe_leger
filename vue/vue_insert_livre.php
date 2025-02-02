<header class="header">
    <h2> Ajout d'un livre </h2>
</header>
<form method="post">
    <table>
        <tr>
            <td> Nom du livre </td>
            <td> <input type="text" name="nomLivre"
                        value="<?= ($leLivre != null) ? $leLivre['nomLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Catégorie du livre </td>
            <td> <input type="text" name="nomCategorie"
                        value="<?= ($leLivre != null) ? $leLivre['nomCategorie'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Auteur du livre </td>
            <td> <input type="text" name="auteurLivre"
                        value="<?= ($leLivre != null) ? $leLivre['auteurLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Lien du livre (image) </td>
            <td> <input type="text" name="imageLivre"
                        value="<?= ($leLivre != null) ? $leLivre['imageLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Maison d'édition du livre </td>
            <td> <input type="text" name="nomMaisonEdition"
                        value="<?= ($leLivre != null) ? $leLivre['nomMaisonEdition'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Nombre d'exemplaires du livre </td>
            <td> <input type="text" name="exemplaireLivre"
                        value="<?= ($leLivre != null) ? $leLivre['exemplaireLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Prix du livre (. au lieu de ,)</td>
            <td> <input type="text" name="prixLivre"
                        value="<?= ($leLivre != null) ? $leLivre['prixLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> <input type="reset" name="Annuler" value="Annuler" class="table-success"> </td>
            <td>
                <?php if ($leLivre != null): ?>
                    <input type="submit" name="Modifier" value="Modifier" class="table-success">
                <?php else: ?>
                    <input type="submit" name="ValiderInsert" value="Valider" class="table-success">
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <?php if ($leLivre != null): ?>
        <input type="hidden" name="idLivre" value="<?= $leLivre['idLivre'] ?>">
    <?php endif; ?>
</form>

<br><br>

<style>
    .header {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 20px;
    }
</style>
