<?php ob_start(); ?> 
<h1>User Settings</h1>

<form class="center" action="?p=home.avatarUpload" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<a href="">Changer de mot de passe</a>

<?php
$content = ob_get_clean();
require('base.php');