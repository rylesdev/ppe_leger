<?php
if (empty($isAdmin)) {
    echo "<h3 style='color: red;'>Vous n'avez pas les permissions pour accéder à cette page.</h3>";
} else {
    require_once("vue/statistique/vue_statistique.php");
}