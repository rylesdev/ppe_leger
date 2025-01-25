<h2> Modifier vos informations </h2>

<form method="post" action="index.php?page=5">
    <table>
        <tr>
            <td><label for="nomUser">Nom :</label></td>
            <td><input type="text" class="form-control" id="nomUser" name="nomUser" required></td>
        </tr>
        <tr>
            <td><label for="prenomUser">Prénom :</label></td>
            <td><input type="text" class="form-control" id="prenomUser" name="prenomUser" required></td>
        </tr>
        <tr>
            <td><label for="emailUser">Email :</label></td>
            <td><input type="email" class="form-control" id="emailUser" name="emailUser" required></td>
        </tr>
        <tr>
            <td><label for="mdpUser">Mot de passe :</label></td>
            <td><input type="password" class="form-control" id="mdpUser" name="mdpUser" required></td>
        </tr>
        <tr>
            <td><label for="adresseUser">Adresse :</label></td>
            <td><input type="text" class="form-control" id="adresseUser" name="adresseUser" required></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="UpdateUser" value="Mettre à jour" class="btn btn-success">
            </td>
        </tr>
    </table>
</form>