<?php
$titrePage = "Ajouter une promotion";
require_once("includes/header.php");
?>

<link rel="stylesheet" href="src/vue/promotion/vue_promotion.css">

    <div class="container">
        <form method="post">
            <div class="form-group">
                <label for="nomLivre">Nom du Livre :</label>
                <input type="text" name="nomLivre" required>
            </div>

            <div class="form-group">
                <label for="dateFinPromotion">Date de Fin de Promotion :</label>
                <input type="date" name="dateFinPromotion" required>
            </div>

            <div class="form-group">
                <label for="prixPromotion">Réduction promotionnelle (%) :</label>
                <input type="number" name="prixPromotion" step="0.01" required>
            </div>

            <button type="submit" name="ValiderPromotion" class="table-success">Valider Promotion</button>
        </form>
    </div>

<?php
require_once("includes/footer.php");
?>