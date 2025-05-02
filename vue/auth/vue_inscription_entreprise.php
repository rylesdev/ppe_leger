<link rel="stylesheet" href="includes/css/auth/vue_inscription.css">

<form method="post" id="entrepriseForm">
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
            <td>Numéro de SIRET <span class="required">*</span></td>
            <td>
                <input type="text" name="siretUser" class="form-input" required
                       placeholder="14 chiffres"
                       oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                       maxlength="14">
                <div class="hint">14 chiffres sans espace</div>
            </td>
        </tr>
        <tr>
            <td>Raison Sociale <span class="required">*</span></td>
            <td><input type="text" name="raisonSocialeUser" class="form-input" required></td>
        </tr>
        <tr>
            <td>Capital Social (€) <span class="required">*</span></td>
            <td>
                <input type="text" name="capitalSocialUser" class="form-input" required
                       placeholder="Ex: 15000.50"
                       oninput="formatDecimal(this)">
                <div class="hint">Format: 15000 ou 15000.50</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required-hint"><small>* Champs obligatoires</small></td>
        </tr>
        <tr>
            <td></td>
            <td class="buttons-cell">
                <input type="reset" name="Annuler" value="Annuler" class="btn btn-secondary">
                <input type="submit" name="InscriptionEntreprise" value="Inscription" class="btn btn-primary">
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
