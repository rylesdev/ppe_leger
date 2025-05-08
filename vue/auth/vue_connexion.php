<div class="auth-form-container">
    <form method="post" class="space-y-4">
        <div class="auth-form-group">
            <label for="emailUser" class="auth-form-label">
                Adresse Email <span class="auth-required">*</span>
            </label>
            <input type="email" name="emailUser" id="emailUser" required
                   class="auth-form-input">
        </div>

        <div class="auth-form-group">
            <label for="mdpUser" class="auth-form-label">
                Mot de passe <span class="auth-required">*</span>
            </label>
            <input type="password" name="mdpUser" id="mdpUser" required
                   class="auth-form-input">
        </div>

        <div class="flex justify-between items-center pt-4">
            <button type="reset" name="Annuler"
                    class="auth-btn auth-btn-secondary">
                Annuler
            </button>
            <button type="submit" name="Connexion"
                    class="auth-btn auth-btn-primary">
                Connexion
            </button>
        </div>

        <div class="auth-footer">
            <small><span class="auth-required">*</span> Champs obligatoires</small>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="#" class="auth-link">Mot de passe oublié ?</a>
    </div>
</div>