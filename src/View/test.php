<?php ob_start(); ?> 
<h1><?= $text ?></h1>
<?php
$content = ob_get_clean();
require('base.php');
