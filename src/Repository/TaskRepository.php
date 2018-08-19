<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQuery;

class TaskRepository extends Repository
{

    public function newTask($task)
    {
        $DBConnection = $this->getDBConnection();
        if ($task['startTime'] == null) $task['startTime'] = time();
        if (gettype($task['startPos']) == 'array'){
            $task['startPos'] = "[".$task['startPos'][0].",".$task['startPos'][1]."]";
        }
        if (gettype($task['targetPos']) == 'array'){
            $task['targetPos'] = "[".$task['targetPos'][0].",".$task['targetPos'][1]."]";
        }
        $query = $DBConnection->prepare('INSERT INTO game_tasks (
            action, 
            subject, 
            startOrigin, 
            startPos, 
            targetOrigin, 
            targetPos, 
            startTime, 
            endTime, 
            author
        ) VALUES ( 
            \':action\', 
            \':subject\', 
            \':startOrigin\', 
            \':startPos\', 
            \':targetOrigin\', 
            \':targetPos\', 
            :startTime, 
            :endTime, 
            \':author\'
        )');
        $query->bindParam(":action", $task['action'], PDO::PARAM_STR);
        $query->bindParam(":subject", $task['subject'], PDO::PARAM_STR);
        $query->bindParam(":startOrigin", $task['startOrigin'], PDO::PARAM_STR);
        $query->bindParam(":startPos", $task['startPos'], PDO::PARAM_STR);
        $query->bindParam(":targetOrigin", $task['targetOrigin'], PDO::PARAM_STR);
        $query->bindParam(":targetPos", $task['targetPos'], PDO::PARAM_STR);
        $query->bindParam(":startTime", $task['startTime'], PDO::PARAM_INT);
        $query->bindParam(":endTime", $task['endTime'], PDO::PARAM_INT);
        $query->bindParam(":author", $task['author'], PDO::PARAM_STR);
        $query->execute();
    }

    public function removeTask($taskId)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare('DELETE FROM game_tasks WHERE id = :taskId');
        $query->bindParam(":taskId", $taskId, PDO::PARAM_INT);
        $query->execute();
    }

}