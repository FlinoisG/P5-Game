<?php

namespace App\Service;

use App\Model\Service;
use App\Entity\TaskEntity;
use App\Config\GameConfig;
use App\Service\AuthenticationService;
use App\Service\MapService;
use App\Service\MathService;
use App\Service\AttackService;
use App\Controller\BaseController;
use App\Controller\TaskController;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

/**
 * this class will be driven by cron.php
 */
class TaskService extends Service
{

    /**
     * Take a task from the database and execute it if condition are met
     *
     * @param object $tasks
     * @return void
     */
    public function handleTasks($tasks)
    {
        $authenticationService = new AuthenticationService;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $mapService = new MapService;
        
        foreach ($tasks as $task) {
            if ($task->getEndTime() < time()) {
                echo $task->getId() . " " . $task->getAction() . " " . $task->getSubject() . "\n";
                if ($task->getAction() === "buy") {
                    $this->buyTask($task);
                } elseif ($task->getAction() === "build") {
                    $this->buildTask($task);
                } elseif ($task->getAction() === "move") {
                    $this->moveTask($task);
                } elseif ($task->getAction() === "attackMove") {
                    $attackAvailable = true;
                    foreach ($tasks as $taskBis) {
                        if ($taskBis->getAction() === "attack" && $taskBis->getTargetOrigin() === $task->getTargetOrigin()) {
                            $taskSoldierAmount = explode(",", $task->getSubject())[1];
                            var_dump($taskSoldierAmount);
                            $taskBisSoldierAmount = explode(",", $taskBis->getSubject())[1];
                            $newSoldierAmount = $taskBisSoldierAmount + $taskSoldierAmount;
                            var_dump($newSoldierAmount);
                            $taskRepository->setAttackSoldiers($newSoldierAmount, $taskBis->getId());
                            $attackAvailable = false;
                        }
                    }
                    if ($attackAvailable){
                        $this->attackMoveTask($task);
                    }
                } elseif ($task->getAction() === "attack") {
                    $this->attackTask($task);
                }
                $taskRepository->removeTask($task->getId());
            }
        }
    }
    
    public function buyTask($task)
    {
        $baseRepository = new BaseRepository;
        $userRepository = new UserRepository;
        $gameConfig = new GameConfig;

        $scoreSettings = $gameConfig->getScoreSettings();

        $targetType = explode(",", $task->getStartOrigin())[0];
        $targetId = explode(",", $task->getStartOrigin())[1];
        if ($task->getSubject() === "worker" || $task->getSubject() == "soldier") {
            $baseRepository->addUnits($task->getSubject(), $targetId, 1, $targetType);
        } else if($task->getSubject() === "workerSpace" || $task->getSubject() === "soldierSpace") {
            $shortTarget = str_replace("Space", "", $task->getSubject());
            $baseRepository->addSpace($shortTarget, $targetType, $targetId);
        }
        $key = $task->getSubject() . "BuildingScore";
        $score = $scoreSettings[$key];
        $userRepository->addScore($task->getAuthor(), $score);
    }

    public function buildTask($task)
    {
        $mineRepository = new MineRepository;
        $baseRepository = new BaseRepository;
        $userRepository = new UserRepository;
        $gameConfig = new GameConfig;

        $scoreSettings = $gameConfig->getScoreSettings();

        if ($task->getSubject() === "base"){
            $baseRepository->newBase($task->getAuthor(), $task->getTargetPos());
        } elseif ($task->getSubject() === "mine"){
            $mineRepository->newMine($task->getAuthor(), $task->getTargetPos());
        }
        $key = $task->getSubject() . "BuildingScore";
        $score = $scoreSettings[$key];
        $userRepository->addScore($task->getAuthor(), $score);
    }

    public function moveTask($task)
    {
        if ($task->getTargetOrigin() != "") {
            $baseRepository = new BaseRepository;
            $arr = explode(',', $task->getSubject());
            $targetType = $arr[0];
            $targetAmount = $arr[1];
            $targetBuilding = explode(",", $task->getTargetOrigin())[0];
            $targetId = explode(",", $task->getTargetOrigin())[1];
            $baseRepository->addUnits($targetType, $targetId, $targetAmount, $targetBuilding);
        }
    }

    public function attackMoveTask($task)
    {
        if ($task->getTargetOrigin() != "") {
            $this->attackTask($task);
        }
    }
    
    public function attackTask($task)
    {
        $baseRepository = new BaseRepository;
        $attackService = new AttackService;

        $targetBuilding = explode(",", $task->getTargetOrigin())[0];
        $targetId = explode(",", $task->getTargetOrigin())[1];

        $targetSoldierAmount = $baseRepository->getUnits("soldier", $targetId, $targetBuilding);
        $targetHP = $baseRepository->getHP($targetId, $targetBuilding);
        $arr = explode(',', $task->getSubject());
        $attackSoldierAmount = $arr[1];

        $attackService->attack($task);
    }

}