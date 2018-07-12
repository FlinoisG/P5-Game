<?php ob_start(); ?> 
<h1>Home</h1>
<div id="board">
    <div id="panel">
        <h1 class="userTitle"><?= $_SESSION['auth'] ?></h1>
        <img class="userAvatar" src="../deposit/User_Avatar/<?= $_SESSION['auth'] ?>.png" class="panelUserAvatar" alt="User Avatar">
        <p>Metal : <?= $metal ?></p>
        <div id="panelInterface"></div>
    </div>
    <div id="mapid" class="unselectable" ></div>
    <div id="minimapid" class="unselectable" ></div>
</div>

<?php
$content = ob_get_clean();
require('base.php');