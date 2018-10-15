<?php

namespace App\Controller;

use App\Service\AvatarService;
use App\Service\RankingService;
use App\Service\GameManagerService;
use App\Service\HomeService;
use App\Repository\UserRepository;
use App\Repository\LastScoreRepository;
use DateTime;

class HomeController extends DefaultController
{

    /**
     * Requires the home page
     *
     * @return void
     */
    public function home()
    {
        
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $homeService = new HomeService;
        
        $script = $homeService->getHomeScripts();
        
        $scriptHead = $script[0];
        $scriptBody = $script[1];
        $metal = $script[2];

        if (isset($_GET["notif"])){
            $notification = $homeService->notification($_GET["notif"]);
        }

        $resetDate = json_decode(file_get_contents(__DIR__.'/../../deposit/ResetDate.json'), true);
        $datetime1 = new DateTime();
        $datetime2 = new DateTime('@'.$resetDate);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('Fin de partie : %a jours, <span id="timerJS">%h:%i:%S</span>');
        $resetTimeLeft = $elapsed;

        $customStyle = $this->setCustomStyle('panel');     
        $title = 'Home';
        
        if (isset($_SESSION['auth'])){
            if (file_exists("../deposit/User_Avatar/".$_SESSION['auth'].".jpg")){
                $avatar = $_SESSION['auth'].".jpg";
            } else {
                $avatar = $_SESSION['auth'].".png";
            }
        }
        
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        
        if ($_SESSION) {
            require('../src/View/HomeView.php');
        } else {
            require('../src/View/VisitorHomeView.php');
        }
        
    }

    /**
     * requires the help page
     *
     * @return void
     */
    public function help() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'Aide';
        $customStyle = $this->setCustomStyle('help');
        require('../src/View/HelpView.php');
    }

    public function test()
    {
        $lastScoreRepository = new LastScoreRepository;
        $lastScoreRepository->setLastWinners(5, 7, 8);
    }

    /**
     * requires the user settings page
     *
     * @return void
     */
    public function settings() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'User Settings';
        require('../src/View/UserSettingsView.php');
    }

    /**
     * Used to call the avatar upload 
     * function and redirect to home page
     *
     * @return void
     */
    public function avatarUpload() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $avatarService = new AvatarService;
        $avatarService->avatarUpload($_FILES);
        header('Location: ?p=home');
    }

    /**
     * requires the ranking page
     *
     * @return void
     */
    public function ranking() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $rankingService = new RankingService;
        $lastScoreRepository = new LastScoreRepository;
        $userRepository = new UserRepository;

        $customStyle = $this->setCustomStyle('ranking');

        $lastScores = $lastScoreRepository->getLastWinners();

        $firstWinner = $userRepository->getUsernameWithId($lastScores[0]["playerId"]);
        $secondWinner = $userRepository->getUsernameWithId($lastScores[1]["playerId"]);
        $thirdWinner = $userRepository->getUsernameWithId($lastScores[2]["playerId"]);

        $firstScore = $lastScores[0]["score"] . "pts";
        $secondScore = $lastScores[1]["score"] . "pts";
        $thirdScore = $lastScores[2]["score"] . "pts";

        $scoreRanking = $rankingService->getRankingByScore();
        $bestScoreRanking = $rankingService->getRankingByBestScore();
        $totalScoreRanking = $rankingService->getRankingByTotalScore();
        
        $title = 'Ranking';
        require('../src/View/RankingView.php');
    }

    //debug
    public function endGame()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $gameManagerService = new GameManagerService;
        $gameManagerService->createNewGame();
    }

}