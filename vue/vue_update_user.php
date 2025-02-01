<h2> Modifier vos informations </h2>

<?php
if (isset($idUser)) {
    $userParticulier = $unControleur->selectParticulier($idUser);
    $userEntreprise = $unControleur->selectEntreprise($idUser);

    if ($userParticulier) {
        echo "<h3> Particulier </h3>";
        ?>
        <form method="post">
            <table>
                <tr>
                    <td><label for="emailUser">Email :</label></td>
                    <td><input type="email" class="form-control" id="emailUser" name="emailUser" required></td>
                </tr>
                <tr>
                    <td><label for="mdpUser">Mot de passe :</label></td>
                    <td><input type="password" class="form-control" id="mdpUser" name="mdpUser" required></td>
                </tr>
                <tr>
                    <td><label for="nomUser">Nom :</label></td>
                    <td><input type="text" class="form-control" id="nomUser" name="nomUser" required></td>
                </tr>
                <tr>
                    <td><label for="prenomUser">Prénom :</label></td>
                    <td><input type="text" class="form-control" id="prenomUser" name="prenomUser" required></td>
                </tr>
                <tr>
                    <td><label for="adresseUser">Adresse :</label></td>
                    <td><input type="text" class="form-control" id="adresseUser" name="adresseUser" required></td>
                </tr>
                <tr>
                    <td><label for="dateNaissanceUser">Date de naissance :</label></td>
                    <td><input type="date" class="form-control" id="dateNaissanceUser" name="dateNaissanceUser" required></td>
                </tr>
                <tr>
                    <td><label for="sexeUser">Sexe :</label></td>
                    <td><select class="form-control" id="sexeUser" name="sexeUser" required>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="UpdateParticulier" value="Mettre à jour" class="btn btn-success">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    } elseif ($userEntreprise) {
        echo "<h3> Entreprise </h3>";
        ?>
        <form method="post">
            <table>
                <tr>
                    <td><label for="emailUser">Email :</label></td>
                    <td><input type="email" class="form-control" id="emailUser" name="emailUser" required></td>
                </tr>
                <tr>
                    <td><label for="mdpUser">Mot de passe :</label></td>
                    <td><input type="password" class="form-control" id="mdpUser" name="mdpUser" required></td>
                </tr>
                <tr>
                    <td><label for="siretUser">SIRET :</label></td>
                    <td><input type="text" class="form-control" id="siretUser" name="siretUser" required></td>
                </tr>
                <tr>
                    <td><label for="raisonSocialeUser">Raison sociale :</label></td>
                    <td><input type="text" class="form-control" id="raisonSocialeUser" name="raisonSocialeUser" required></td>
                </tr>
                <tr>
                    <td><label for="capitalSocialUser">Capital social :</label></td>
                    <td><input type="number" class="form-control" id="capitalSocialUser" name="capitalSocialUser" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="UpdateEntreprise" value="Mettre à jour" class="btn btn-success">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    } else {
        echo '<p>Aucun utilisateur sélectionné.</p>';
    }
}
?>
