<?php

namespace App\Service;

use App\Model\Service;
use App\Service\AuthenticationService;
use App\Service\MapService;
use App\Controller\BaseController;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\TaskRepository;

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
            if ($task["endTime"] < time()) {
                if ($task["action"] === "buy") {
                    $this->buyTask($task);
                } elseif ($task["action"] === "build") {
                    $this->buildTask($task);
                } elseif ($task["action"] === "move") {
                    $this->moveTask($task);
                } elseif ($task["action"] === "attack") {
                    $this->attackTask($task);
                }
                $taskRepository->removeTask($task["id"]);
            }
        }
    }

    public function buyTask($task)
    {
        $targetType = explode(",", $task["startOrigin"])[0];
        $targetId = explode(",", $task["startOrigin"])[1];
        if ($task["subject"] === "worker" || $task["subject"] == "soldier") {
            $baseRepository->addUnits($task["subject"], $targetId, 1, $targetType);
        } else if($task["subject"] === "workerSpace" || $task["subject"] === "soldierSpace") {
            $shortTarget = str_replace("Space", "", $task["subject"]);
            $baseRepository->addSpace($shortTarget, $targetType, $targetId);
        }
    }

    public function buildTask($task)
    {
        if ($task["subject"] === "base"){
            $baseRepository->newBase($task["author"], $task["targetPos"]);
        } elseif ($task["subject"] === "mine"){
            $mineRepository->newMine($task["author"], $task["targetPos"]);
        }
        //$mapService->build($task["subject"], $task["targetPos"], $task["author"]);
    }

    public function moveTask($task)
    {
        if ($task["targetOrigin"] != "") {
            $arr = explode(',', $task["subject"]);
            $targetType = $arr[0];
            $targetAmount = $arr[1];
            $targetBuilding = explode(",", $task["targetOrigin"])[0];
            $targetId = explode(",", $task["targetOrigin"])[1];
            $baseRepository->addUnits($targetType, $targetId, $targetAmount, $targetBuilding);
        }
    }

    public function attackTask($task)
    {
        if ($task["targetOrigin"] != "") {
            $arr = explode(',', $task["subject"]);
            //$targetType = $arr[0];
            $soldierAmount = $arr[1];

            $targetBuilding = explode(",", $task["targetOrigin"])[0];
            $targetId = explode(",", $task["targetOrigin"])[1];
            $targetSoldierAmount = $baseRepository->getUnits("soldier", $targetId, $targetBuilding);

            if ($targetSoldierAmount > $soldierAmount){
                $negativeAmount = $soldierAmount * -1;
                $baseRepository->addUnits("soldier", $targetId, $negativeAmount, $targetBuilding);
            } else {
                $baseRepository->captureBase();
            }

            $baseRepository->addUnits($targetType, $targetId, $targetAmount, $targetBuilding);
        }
    }

}