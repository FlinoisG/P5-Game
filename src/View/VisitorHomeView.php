<?php ob_start(); ?> 
<h1>Home</h1>
<div id="board">
    <div id="mapid" class="unselectable" ></div>
</div>

<?php
$content = ob_get_clean();
require('base.php');