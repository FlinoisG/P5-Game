<?php ob_start(); ?> 
<h1>Mot de Passe Oublié</h1>
<div class="center">
    <form action="?p=login&recovery" method="post">
        <div class="form-group">
            <label for="loginUsername">Adresse mail</label>
            <input class="form-control login-form" type="email" id="loginEmail" name="email" placeholder="Adresse mail" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require('base.php');
