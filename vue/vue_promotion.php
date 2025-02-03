<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
    Ajouter une promotion
</header>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Promotion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 50px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #2c6e49;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
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
