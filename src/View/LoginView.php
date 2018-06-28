<?php ob_start(); ?> 
<h1>Connection</h1>
<div class="center col-lg-4">
    <span>Connection</span>
    <a href="?p=login.register">S'inscrire</a>
</div>
<div class="center col-lg-4">
    <form action="?p=login&login" method="post">
        <div class="form-group">
            <label for="loginUsername">Nom du compte</label>
            <input class="form-control login-form" type="text" id="loginUsername" name="username" placeholder="Nom du compte" required>
        </div>
        <div class="form-group">
            <label for="loginPassword">Mot de passe</label>
            <input class="form-control login-form" type="password" id="loginPassword" name="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
        <br>
        <?= $link ?>
    </form>
</div>
<?php
$content = ob_get_clean();
require('base.php');
