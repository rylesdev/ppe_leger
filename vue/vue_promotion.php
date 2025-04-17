<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
    <link rel="stylesheet" href="includes/css/vue_promotion.css">
    Ajouter une promotion
</header>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Promotion</title>
</head>
<body>
<form method="post">
    <label for="nomLivre">Nom du Livre :</label>
    <input type="text" name="nomLivre" required>

    <label for="dateFinPromotion">Date de Fin de Promotion :</label>
    <input type="date" name="dateFinPromotion" required>

    <label for="prixPromotion">Prix Promotionnel (€) :</label>
    <input type="number" name="prixPromotion" step="0.01" required>

    <button type="submit" name="ValiderPromotion">Valider Promotion</button>
</form>

</body>
</html>
