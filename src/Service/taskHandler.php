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
            var_dump($task);
            if ($task["endTime"] < time()) {
                if ($task["action"] == "buy") {
                    if ($task["subject"] == "worker" || $task["subject"] == "soldier") {
                        //$arr = explode(",", $task[""]);
                        //$startOriginType = $arr[0];
                        //$auth->buyUnit($task["subject"], $task["startOrigin"]);
                    } else {
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
                    }
                }
                $auth->removeTask($task["id"]);
            }
        }
    }

}