<?php

namespace App\Service;

use App\Model\Service;
use App\Service\Auth;
use App\Controller\BaseController;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;

/**
 * this class will be driven by cron.php
 */
class TaskHandler extends Service
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
        var_dump(time());
        foreach ($tasks as $task) {
            var_dump($task["endTime"]);
            if ($task["endTime"] < time()) {
                if ($task["action"] == "buy") {
                    if ($task["subject"] == "worker" || $task["subject"] == "soldier") {
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
                        var_dump($arr);
                        $targetType = $arr[0];
                        $targetAmount = $arr[1];
                        $targetBuilding = explode(",", $task["targetOrigin"])[0];
                        $targetId = explode(",", $task["targetOrigin"])[1];
                        $baseRepository->buyUnits($targetType, $targetId, $targetAmount, $targetBuilding);
                    }
                }
                $auth->removeTask($task["id"]);
            }
        }
    }

}