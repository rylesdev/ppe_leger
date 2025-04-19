<link rel="stylesheet" href="includes/css/auth/vue_inscription.css">

<form method="post">
    <table>
        <tr>
            <td> Email </td>
            <td><input type="email" name="emailUser"></td>
        </tr>
        <tr>
            <td> Mot de Passe </td>
            <td><input type="password" name="mdpUser"></td>
        </tr>
        <tr>
            <td> Adresse Postale </td>
            <td><input type="text" name="adresseUser"></td>
        </tr>
        <tr>
            <td> Nom </td>
            <td><input type="text" name="nomUser"></td>
        </tr>
        <tr>
            <td> Prenom </td>
            <td><input type="text" name="prenomUser"></td>
        </tr>
        <tr>
            <td> Date de Naissance </td>
            <td><input type="date" name="dateNaissanceUser"></td>
        </tr>
        <tr>
            <td>Sexe</td>
            <td>
                <select name="sexeUser">
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </td>
        </tr>
        <tr>
            <td> <input type="reset" name="Annuler" value="Annuler" class="table-success"> </td>
            <td><input type="submit" name="InscriptionParticulier" value="Inscription" class="table-success"></td>
        </tr>
    </table>
</form>