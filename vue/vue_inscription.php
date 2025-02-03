<h2> Inscription </h2>

<?php
echo '<a href="index.php?inscription=particulier"><button class="btn-green">Vous êtes un particulier</button></a><br>';
echo '<a href="index.php?inscription=entreprise"><button class="btn-green">Vous êtes une entreprise</button></a><br>';

$inscription = isset($_GET['inscription']) ? $_GET['inscription'] : '';

switch ($inscription) {
    case "particulier":
        require_once("controleur/c_inscription_particulier.php");
        break;
    case "entreprise":
        require_once("controleur/c_inscription_entreprise.php");
        break;
}
?>

<style>
    .btn-green {
        background-color: #2E6E49;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn-green:hover {
        background-color: #245c3d;
    }

    .btn-green:active {
        background-color: #1b472f;
    }
</style>