<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de l'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-info {
            margin-bottom: 20px;
        }
        .user-info label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .user-info span {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Informations de l'utilisateur</h2>
    <div class="user-info">
        <label>Nom:</label>
        <span><?php echo $user[0][1]; ?></span>
    </div>
    <div class="user-info">
        <label>Prénom:</label>
        <span><?php echo $user[0][2]; ?></span>
    </div>
    <div class="user-info">
        <label>Email:</label>
        <span><?php echo $user[0][3]; ?></span>
    </div>
    <div class="user-info">
        <label>Adresse:</label>
        <span><?php echo $user[0][5]; ?></span>
    </div>
</div>
</body>
</html>