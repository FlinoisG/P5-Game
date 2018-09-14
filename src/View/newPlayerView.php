<?php ob_start(); ?> 
<h1>Home</h1>
<div id="board">
    <div id="panel">
        <h1 class="userTitle"><?= $_SESSION['auth'] ?></h1>
        <img class="userAvatar" src="../deposit/User_Avatar/<?= $_SESSION['auth'] ?>.png" class="panelUserAvatar" alt="User Avatar">
        <p>Metal : <?= $metal ?></p>
        <div id="panelInterface">
            <div id="newUserPanel" class="newUserPanel">
                <h2 id="newUserTitle" class="newUserTitle">Bienvenu !</h2>
                <p id="newUserText" class="newUserText">
                    Construisez votre première base en cliquant sur l'icone sous ce texte puis sur un emplacement sur la carte. 
                    Si c'est votre première partie, il est conseillé de construire votre première base loins 
                    des autres et près d'un gisement de metal.</p>
                <p id="newUserText2" class="newUserText">
                    Comme c'est votre première base, elle est gratuite 
                    et se construit instantanément.</p>
                <form action="#" id="newUserSubPanel" class="newUserSubPanel panelSubOption">
                    <div id="newUserInner" class="newUserInner optionInner">
                        <input type="image" id="newUserPanelIcon" class="newUserPanelIcon panelSubIcon" src="../public/assets/img/unit_slot_base.png">
                        <span id="newUserPanelText" class="newUserPanelText panelSubText">Acheter base</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="mapid" class="unselectable" ></div>
    <div id="minimapid" class="unselectable" ></div>
</div>

<?php
$content = ob_get_clean();
require('base.php');