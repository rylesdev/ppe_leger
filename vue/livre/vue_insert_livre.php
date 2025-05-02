<?php
$titrePage = "Ajout d'un livre";
require_once("includes/header.php");

$categorie = $unControleur->executerRequete("SELECT nomCategorie FROM categorie");

$maisonEdition = $unControleur->executerRequete("SELECT nomMaisonEdition FROM maisonEdition");

$promotion = $unControleur->executerRequete("SELECT nomPromotion FROM promotion");
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
            <td>Nom du livre <span class="required">*</span></td>
            <td><input type="text" name="nomLivre" value="<?= ($leLivre != null) ? $leLivre['nomLivre'] : '' ?>" required></td>
        </tr>
        <tr>
            <td>Auteur du livre <span class="required">*</span></td>
            <td><input type="text" name="auteurLivre" value="<?= ($leLivre != null) ? $leLivre['auteurLivre'] : '' ?>" required></td>
        </tr>
        <tr>
            <td>Lien de l'image du livre <span class="required">*</span></td>
            <td><input type="text" name="imageLivre" value="<?= ($leLivre != null) ? $leLivre['imageLivre'] : '' ?>" required></td>
        </tr>
        <tr>
            <td>Nombre d'exemplaires <span class="required">*</span></td>
            <td><input type="number" name="exemplaireLivre" value="<?= ($leLivre != null) ? $leLivre['exemplaireLivre'] : '' ?>" required></td>
        </tr>
        <tr>
            <td>Prix du livre (utiliser . au lieu de ,) <span class="required">*</span></td>
            <td><input type="text" name="prixLivre" value="<?= ($leLivre != null) ? $leLivre['prixLivre'] : '' ?>" required></td>
        </tr>
        <tr>
            <td>Catégorie <span class="required">*</span></td>
            <td>
                <select name="nomCategorie" required>
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <?php foreach ($categorie as $uneCategorie): ?>
                        <option value="<?= $uneCategorie ?>" <?= ($leLivre != null && $leLivre['nomCategorie'] === $uneCategorie) ? 'selected' : '' ?>>
                            <?= $uneCategorie ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Maison d'édition <span class="required">*</span></td>
            <td>
                <select name="nomMaisonEdition" required>
                    <option value="">-- Sélectionnez une maison d'édition --</option>
                    <?php foreach ($maisonEdition as $uneMaisonEdition): ?>
                        <option value="<?= $uneMaisonEdition ?>" <?= ($leLivre != null && $leLivre['nomMaisonEdition'] === $uneMaisonEdition) ? 'selected' : '' ?>>
                            <?= $uneMaisonEdition ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Promotion <span class="required">*</span></td>
            <td>
                <select name="nomPromotion" required>
                    <option value="">-- Sélectionnez une promotion --</option>
                    <?php foreach ($promotion as $unePromotion): ?>
                        <option value="<?= $unePromotion ?>" <?= ($leLivre != null && $leLivre['nomPromotion'] === $unePromotion) ? 'selected' : '' ?>>
                            <?= $unePromotion ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required-hint"><small>* Champs obligatoires</small></td>
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

<style>
    .required {
        color: red;
    }
    .required-hint {
        text-align: right;
        font-size: 0.8em;
        color: #666;
    }
</style>

<br><br>

<?php
require_once("includes/footer.php");
?>
