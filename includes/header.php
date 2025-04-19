<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if (isset($cssPage)) {
        echo "<link rel='stylesheet' href='includes/css/".$cssPage.".css'>";
    }
    ?>
    <title><?php echo $titrePage ?? "Page"; ?></title>
</head>
<body>
<header class="header">
    <h1><?php echo $titrePage ?? "Titre par défaut"; ?></h1>
</header>