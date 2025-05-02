<link rel="stylesheet" href="includes/css/auth/vue_inscription.css">

<form method="post" id="particulierForm">
    <table class="inscription-table">
        <tr>
            <td>Email <span class="required">*</span></td>
            <td><input type="email" name="emailUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Mot de Passe <span class="required">*</span></td>
            <td><input type="password" name="mdpUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Adresse Postale <span class="required">*</span></td>
            <td><input type="text" name="adresseUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Nom <span class="required">*</span></td>
            <td><input type="text" name="nomUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Prénom <span class="required">*</span></td>
            <td><input type="text" name="prenomUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Date de Naissance <span class="required">*</span></td>
            <td><input type="date" name="dateNaissanceUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Sexe <span class="required">*</span></td>
            <td>
                <select name="sexeUser" class="form-input" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required-hint"><small>* Champs obligatoires</small></td>
        </tr>
        <tr>
            <td></td>
            <td class="buttons-cell">
                <input type="reset" name="Annuler" value="Annuler" class="btn btn-secondary">
                <input type="submit" name="InscriptionParticulier" value="Inscription" class="btn btn-primary">
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
    select[required]:invalid {
        color: gray;
    }
    .inscription-table {
        width: 100%;
        border-collapse: collapse;
    }
    .form-input {
        width: 100%;
        padding: 8px;
        margin: 4px 0;
        box-sizing: border-box;
    }
    .buttons-cell {
        text-align: right;
    }
    .btn {
        padding: 10px 15px;
        margin: 5px;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #007BFF;
        color: white;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
</style>
