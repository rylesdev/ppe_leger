<link rel="stylesheet" href="includes/css/vue_user.css">

    <div class="form-container">
        <h2>Supprimer votre compte</h2>

        <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.');">
            <div class="warning-box">
                <p><strong>Attention :</strong> La suppression de votre compte est définitive et entrainera la perte de toutes vos données.</p>
            </div>

            <div class="form-group" style="text-align: center; margin-top: 30px;">
                <input type="submit" name="DeleteUser" value="Supprimer définitivement mon compte"
                       class="btn btn-danger" style="background-color: #dc3545;">
            </div>
        </form>
    </div>

<?php
require_once("includes/footer.php");
?>