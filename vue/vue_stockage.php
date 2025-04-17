<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
    <link rel="stylesheet" href="includes/css/vue_stockage.css">
Stockage des livres
</header>
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
<br>
<br>