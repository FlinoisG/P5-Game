<?php

namespace App\Controller;

use App\Controller\HomeController;
use App\Service\AuthenticationService;
use App\Service\MathService;
use App\Service\EntitiesService;
use App\Service\MapService;
use App\Service\TaskControllerService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use App\Entity\TaskEntity;
use App\Config\GameConfig;

class TaskController extends DefaultController
{

    /**
     * Request TaskControllerService if user can buy unit
     *
     * @return void
     */
    public function buy()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $taskControllerService = new TaskControllerService;

        if (isset($_GET["pos"])){
            $targetPos = $_GET["pos"];
        } else {
            $targetPos = null;
        }

        $header = $taskControllerService->buy($_GET['origin'], $_GET['type'], $targetPos);

        if (!is_null($header)){
            header($header);
        }
    }
    
    /**
     * Request TaskControllerService if user can move unit
     *
     * @param string $type
     * @param string $startOrigin
     * @param string $target
     * @param integer $amount
     * @param boolean $isBuilding
     * @return void
     */
    public function moveUnit($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $taskControllerService = new TaskControllerService;

        if ($type == null) $type = $_GET['type'];
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = (int)$_GET['amount'];

        $header = $taskControllerService->moveUnit($type, $startOrigin, $target, $amount, $isBuilding);

        if (!is_null($header)){
            header($header);
        }
        
    }

    /**
     * Request TaskControllerService if user can attack
     *
     * @param string $startOrigin
     * @param string $target
     * @param integer $amount
     * @return void
     */
    public function attack($startOrigin=null, $target=null, $amount=1)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }

        $taskControllerService = new TaskControllerService;

        
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = (int)$_GET['amount'];

        $header = $taskControllerService->attack($startOrigin, $target, $amount);

        if (!is_null($header)){
            header($header);
        }
        
    }

    /**
     * Request TaskControllerService if user 
     * can create his first base
     *
     * @return void
     */
    public function newUserBase()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $taskControllerService = new TaskControllerService;
        $homeController = new HomeController;
        $userRepository = new UserRepository;
        
        if ($userRepository->getNewUserWithUsername($_SESSION['auth']) != 1) {
            die($this->error('403'));
        }
        $pos = $_GET["pos"];

        $taskControllerService->newUserBase($pos, $_SESSION['authId']);
        $homeController->home();
    }

}
