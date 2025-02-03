<br>
<h2> Connexion </h2>

<form method="post">
    <table>
        <tr>
            <td> Adresse Email </td>
            <td><input type="text" name="emailUser"></td>
        </tr>
        <tr>
            <td> MDP </td>
            <td><input type="password" name="mdpUser"></td>
        </tr>
        <tr>
            <td> <input type="reset" name="Annuler" value="Annuler" class="btn-green"> </td>
            <td><input type="submit" name="Connexion" value="Connexion" class="btn-green"></td>
        </tr>
    </table>
</form>

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