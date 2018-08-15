<?php

namespace App\Service;

use App\Service\Auth;
use App\Controller\BaseController;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;

/**
 * this class will be driven by cron.php
 */
class TaskHandler
{

    /**
     * Take a task from the database and execute it if condition are met
     *
     * @param object $tasks
     * @return void
     */
    public function handleTasks($tasks)
    {
        $auth = new Auth;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        foreach ($tasks as $task) {
            if ($task["endTime"] < time()) {
                if ($task["action"] == "buy") {
                    if ($task["subject"] == "worker" || $task["subject"] == "soldier") {
                        //$arr = explode(",", $task[""]);
                        //$startOriginType = $arr[0];
                        //$auth->buyUnit($task["subject"], $task["startOrigin"]);
                        $targetType = explode(",", $task["startOrigin"])[0];
                        $targetId = explode(",", $task["startOrigin"])[1];
                        $baseRepository->buyUnits($task["subject"], $targetId, 1, $targetType);
                    } else if($task["subject"] == "workerSpace" || $task["subject"] == "soldierSpace") {
                        $shortTarget = str_replace("Space", "", $task["subject"]);
                        $auth->buySpace($shortTarget, $task["startOrigin"]);
                    }
                } elseif ($task["action"] == "build") {
                    $auth->build($task["subject"], $task["targetPos"], $task["author"]);
                } elseif ($task["action"] == "move") {
                    if ($task["targetOrigin"] != "") {
                        $arr = explode(',', $task["subject"]);
                        $targetType = $arr[0];
                        $targetAmount = $arr[1];
                        //$auth->buyUnit($targetType, $task["targetOrigin"], $targetAmount);
                        $targetBuilding = explode(",", $task["targetOrigin"])[0];
                        $targetId = explode(",", $task["targetOrigin"])[1];
                        $baseRepository->buyUnits($targetType, $targetId, $targetAmount, $targetBuilding);
                        //$baseRepository->buyWorkers($targetType, $targetId, $targetAmount, $targetBuilding);
                        /*
                        if ($targetType === "worker"){
                            if ($targetBuilding === "base"){
                                $baseRepository->buyWorkers($targetId, $targetAmount);
                            } else if ($targetBuilding === "mine"){
                                $mineRepository->buyWorkers($targetId, $targetAmount);
                            }
                        } else if ($type === "soldier") {
                            if ($targetBuilding === "base"){
                                $baseRepository->buySoldiers($targetId, $targetAmount);
                            } else if ($targetBuilding === "mine"){
                                $mineRepository->buySoldiers($targetId, $targetAmount);
                            }
                        }
                        */
                    }
                }
                $auth->removeTask($task["id"]);
            }
        }
    }

}