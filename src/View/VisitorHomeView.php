<?php ob_start(); ?> 
<h1>Home</h1>
<div id="board">
    <div id="panel">
        <li class="nav-item">
            <a class="nav-link" href="?p=login">Connection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?p=login.register">S'inscrire</a>
        </li>
    </div>
    <div id="mapid" class="unselectable" ></div>
</div>

<?php
$content = ob_get_clean();
require('base.php');