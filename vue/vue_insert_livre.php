<form method="post">
	<table>
		<tr>
			<td> Nom du livre </td>
			<td> <input type="text" name="nomLivre"
				value="<?= ($leLivre!=null)? $leLivre['nomLivre'] : '' ?>"></td>
		</tr>
		<tr>
			<td> Catégorie du livre  </td>
			<td> <input type="text" name="categorieLivre"
				value="<?= ($leLivre!=null)? $leLivre['categorieLivre'] : '' ?>"></td>
		</tr>
		<tr>
			<td> Auteur du livre </td>
			<td> <input type="text" name="auteurLivre"
				value="<?= ($leLivre!=null)? $leLivre['auteurLivre'] : '' ?>"></td>
		</tr>
        <tr>
            <td> Lien du livre (image) </td>
            <td> <input type="text" name="imageLivre"
                        value="<?= ($leLivre!=null)? $leLivre['imageLivre'] : '' ?>"></td>
        </tr>
        <tr>
            <td> Prix du livre (. au lieu de ,)</td>
            <td> <input type="text" name="prixLivre"
                        value="<?= ($leLivre!=null)? $leLivre['prixLivre'] : '' ?>"></td>
        </tr>
		<tr>
			<td> <input type="reset" name="Annuler" value="Annuler" class="table-success"> </td>
			<td> <input type="submit" 
				<?= ($leLivre!=null)? ' name="Modifier" value="Modifier" ' :
				' name="ValiderInsert" value="Valider" class="table-success"' ?>
				></td>
		</tr>
	</table>
	<?= ($leLivre!=null) ? '<input type="hidden" name="idLivre" value="'.$leLivre['idLivre'].'">' : '' ?>
</form>

<br>
<br>