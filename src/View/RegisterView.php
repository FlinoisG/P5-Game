<?php ob_start(); ?>
<h1>S'inscrire</h1>
<div class="formTabs">
    <a class="formTab link" href="?p=login.login">Connection</a>
    <div class="formTab disabledLink">S'inscrire</div>
</div>
<div>
    <form action="?p=login.register&register" method="post">
        <div class="form-group">
            <label id="test" for="registerUsername">Nom du compte</label>
            <input class="form-control login-form" type="text" id="registerUsername" name="username" placeholder="Nom du compte" required>
        </div>
        <div class="form-group">
            <label for="registerEmail">Adresse mail</label>
            <input class="form-control login-form" type="email" id="registerEmail" name="email" placeholder="Adresse mail" required>
        </div>
        <div class="form-group">
            <label for="registerUsername">Confirmation de l'adresse mail</label>
            <input class="form-control login-form" type="email" id="registerEmailConf" name="email_conf" placeholder="Adresse mail" required>
        </div>
        <div class="form-group">
            <label for="registerPassword">Mot de passe</label>
            <input class="form-control login-form" type="password" id="registerPassword" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <label for="registerPassword">Confirmation du Mot de passe</label>
            <input class="form-control login-form" type="password" id="registerPasswordConf" name="password_conf" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require('base.php');
