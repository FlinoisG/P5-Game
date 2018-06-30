<?php ob_start(); ?> 
<h1>Home</h1>
<div id="board" class="unselectable" >
    <div id="panel">
        <?= $panel ?>
    </div>
    <div id="mapid" class="unselectable" ></div>
    <div id="minimapid" class="unselectable" ></div>
</div>

<?php
$content = ob_get_clean();
require('base.php');