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
        foreach ($tasks as $task) {
            if ($task["action"] == "buy"){
                if ($task["time"] < time()){
                    $auth = new Auth;
                    $origin = (int)preg_replace('/[^0-9.]+/', '', $task["origin"]);
                    if ($task["target"] == "worker"){
                        $auth->buyWorker($origin);
                    } else if ($task["target"] == "soldier"){
                        $auth->buySoldier($origin);
                    }
                    $auth->removeTask($task["id"]);
                }
            }            
        }
    }

}