<?php ob_start(); ?> 
<h1>Mot de Passe Oublié</h1>
<div class="center col-lg-4">
    <form action="?p=login.newPassword&user=<?= $user ?>" method="post">
        <div class="form-group">
            <label for="registerPassword">Mot de passe</label>
            <input class="form-control login-form" type="password" id="recoveryPassword" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <label for="registerPassword">Confirmation du Mot de passe</label>
            <input class="form-control login-form" type="password" id="recoveryPasswordConf" name="password_conf" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require('base.php');
