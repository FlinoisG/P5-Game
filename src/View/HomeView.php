<?php ob_start(); ?> 
<h1>Home</h1>
<?php
$content = ob_get_clean();
require('base.php');