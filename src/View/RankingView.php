<?php ob_start(); ?> 
<h1><?= $title ?></h1>

<div id="main">
    <div id="mainRanking">

        <div id="scoreRankingDiv" class="rankingDiv">
            <h3>Classement de la partie actuel</h3>
            <table id="scoreRankingTable">
                <tr>
                    <th class="positionScoreTab">Position</th>
                    <th class="avatarScoreTab"></th>
                    <th class="usernameScoreTab">Joueur</th>
                    <th class="pointsScoreTab">Points</th>
                </tr>
                <?php
                $count = 1;
                foreach ($scoreRanking as $key => $value) {
                    echo "
                    <tr>
                    <td>".$count."</td>
                    <td><img src=\"../deposit/User_Avatar/".$key.".png\" class=\"smallAvatar\"></td>
                    <td>".$key."</td>
                    <td>".$value."</td>
                    </tr>";
                    $count++;
                }            
                ?>
            </table>
        </div>
        
        <div id="bestScoreRankingDiv" class="rankingDiv">
            <h3>Meilleur score sur toute les parties</h3>
            <table id="bestScoreRankingTable">
                <tr>
                    <th class="positionScoreTab">Position</th>
                    <th class="avatarScoreTab"></th>
                    <th class="usernameScoreTab">Joueur</th>
                    <th>Points</th>
                </tr>
                <?php
                $count = 1;
                foreach ($bestScoreRanking as $key => $value) {
                    echo "
                    <tr>
                    <td>".$count."</td>
                    <td><img src=\"../deposit/User_Avatar/".$key.".png\" class=\"smallAvatar\"></td>
                    <td>".$key."</td>
                    <td>".$value."</td>
                    </tr>";
                    $count++;
                }            
                ?>
            </table>
        </div>
        
        <div id="totalScoreRankingDiv" class="rankingDiv">
            <h3>Score total</h3>
            <table id="totalScoreRankingTable">
                <tr>
                    <th class="positionScoreTab">Position</th>
                    <th class="avatarScoreTab"></th>
                    <th class="usernameScoreTab">Joueur</th>
                    <th class="pointsScoreTab">Points</th>
                </tr>
                <?php
                $count = 1;
                foreach ($totalScoreRanking as $key => $value) {
                    echo "
                    <tr>
                        <td>".$count."</td>
                        <td><img src=\"../deposit/User_Avatar/".$key.".png\" class=\"smallAvatar\"></td>
                        <td>".$key."</td>
                        <td>".$value."</td>
                    </tr>";
                    $count++;
                }            
                ?>
            </table>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require('base.php');