<?php ob_start(); ?>
<h1>S'inscrire</h1>
<div class="center col-lg-4">
    <a href="?p=login.login">Connection</a>
    <span>S'inscrire</span>
</div>
<div class="center col-lg-4">
    <form action="?p=login.register&register=true" method="post">
        <div class="form-group">
            <label id="test" for="registerUsername">Nom du compte</label>
            <input class="form-control login-form" type="text" id="registerUsername" name="username" placeholder="Nom du compte">
        </div>
        <div class="form-group">
            <label for="registerEmail">Adresse mail</label>
            <input class="form-control login-form" type="text" id="registerEmail" name="email" placeholder="Adresse mail">
        </div>
        <div class="form-group">
            <label for="registerUsername">Confirmation de l'adresse mail</label>
            <input class="form-control login-form" type="text" id="registerEmailConf" name="email_conf" placeholder="Adresse mail">
        </div>
        <div class="form-group">
            <label for="registerPassword">Mot de passe</label>
            <input class="form-control login-form" type="password" id="registerPassword" name="password" placeholder="Mot de passe">
        </div>
        <div class="form-group">
            <label for="registerPassword">Confirmation du Mot de passe</label>
            <input class="form-control login-form" type="password" id="registerPasswordConf" name="password_conf" placeholder="Mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require('base.php');
