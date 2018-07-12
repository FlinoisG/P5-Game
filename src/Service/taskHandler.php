<?php

namespace App\Service;

use App\Service\Auth;
use App\Controller\BaseController;

/**
 * this class will be driven by cron.php
 */
class TaskHandler
{

    public function handleTasks($tasks)
    {
        $auth = new Auth;
        foreach ($tasks as $task) {
            if ($task["action"] == "buy"){
                if ($task["time"] < time()){
                    if ($task["target"] == "worker" || $task["target"] == "soldier"){
                        $auth->buyUnit($task["target"], $task["origin"]);
                    } else {
                        $shortTarget = str_replace("Space", "", $task["target"]);
                        $auth->buySpace($shortTarget, $task["origin"]);
                    }
                }
                $auth->removeTask($task["id"]);
            } else if ($task["action"] == "build"){
                if ($task["time"] < time()) {
                    $auth->build($task["target"], $task["targetPos"], $task["author"]);
                    $auth->removeTask($task["id"]);
                }
            }
        }
    }

}