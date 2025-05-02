<link rel="stylesheet" href="includes/css/auth/vue_connexion.css">

<form method="post">
    <table>
        <tr>
            <td>Adresse Email <span class="required">*</span></td>
            <td><input type="email" name="emailUser" required></td>
        </tr>
        <tr>
            <td>MDP <span class="required">*</span></td>
            <td><input type="password" name="mdpUser" required></td>
        </tr>
        <tr>
            <td colspan="2" class="required-hint"><small>* Champs obligatoires</small></td>
        </tr>
        <tr>
            <td><input type="reset" name="Annuler" value="Annuler" class="btn-green"></td>
            <td><input type="submit" name="Connexion" value="Connexion" class="btn-green"></td>
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
