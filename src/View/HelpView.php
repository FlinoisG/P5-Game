<?php ob_start(); ?>
<div id="helpMenuTitle">Aide</div>
<div id="helpMain">
    <div id="helpMenu">
        <ul class="helpUl">
            <li class="helpLi" id="helpMenu1"><a href="#">Introduction</a></li>
            <li class="helpLi" id="helpMenu2"><a href="#">Interface</a></li>
            <li class="helpLi" id="helpMenu3"><a href="#">Règles</a></li>
        </ul>
    </div>
    <div id="helpPage">
        <h1 id="helpPageTitle"></h1>
        <div id="helpPageContent"></div>
    </div>
</div>
<div class="disabled">
    <div id="title1">
        Introduction et fonctionement de base
    </div>
    <div id="content1">
        <p>Le but du jeu est de faire le plus de points lors d'une partie en se développant économiquement ou militairement.
        Construisez des mines pour vous enrichir en matière première, des bases pour produire des ouvriers et des soldats.</p>

        <p>Chaque ouvrier dans une mine à proximitée  d'un gisement de fer raportera 10 de fer toute les 5 minutes.
        Chaque soldat dans un batiment sera considérer comme en garnison et défendra le batiment en cas d'attaque.</p>

        <p>Lors d'une attaque contre un batiment, si ce batiment contiens des soldats, ils seront soustraits au nombre de soldat attaquant. 
        Si il reste des soldat ou qu'il n'y avais pas de soldat en défense, le batiment perdra 1 point de vie part soldat attaquant
        immédiatement, puis une foix par heur.</p>

        <p>Lorsque le batiment attaqué n'as plus de point de vie, il est alors capturé ainsi que les ouvriers qu'il continent.</p>
    </div>
    <div id="title2">
        Interface
    </div>
    <div id="content2">
        oui oui oui oui oui oui oui oui oui oui oui oui oui oui 
    </div>
    <div id="title3">
        Règles
    </div>
    <div id="content3">
        non non non non non non non non non non non non non 
    </div>
</div>
<script src="assets/js/helpPageScript.js"></script>
<?php
$content = ob_get_clean();
require('base.php');