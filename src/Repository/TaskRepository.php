<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQueryService;

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
            :action, 
            :subject, 
            :startOrigin, 
            :startPos, 
            :targetOrigin, 
            :targetPos, 
            :startTime, 
            :endTime, 
            :author
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

    public function getTasks($action=null)
    {
        if ($action == null){
            $sqlQueryService = new sqlQueryService();
            $tasks = $sqlQueryService->sqlQueryService("SELECT * FROM game_tasks");
        } else {
            $DBConnection = $this->getDBConnection();
            $query = $DBConnection->prepare("SELECT * FROM game_tasks WHERE action = :action");
            $query->bindParam(":action", $action, PDO::PARAM_STR);
            $query->execute();
            $tasks = $query->fetchAll();
        }
        return $tasks;
    }

    public function getEntityInConst($subject, $baseId)
    {
        $baseOrigin = "base," . $baseId;
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT endTime FROM game_tasks WHERE startOrigin = :baseOrigin AND subject = :subject");
        $query->bindParam(":baseOrigin", $baseOrigin, PDO::PARAM_STR);
        $query->bindParam(":subject", $subject, PDO::PARAM_STR);
        $query->execute();
        $subjectInConstruct = $query->fetch();
        return $subjectInConstruct;
    }

    public function getAllEntityInConst()
    {
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT subject, startOrigin, endTime FROM game_tasks WHERE subject='worker' OR subject='soldier'";
        $entitiesInConstruct = $sqlQueryService->sqlQueryService($query);
        $entityArray = [];
        foreach ($entitiesInConstruct as $entity) {
            if (isset($entityArray[$entity['subject']])){
                $size = sizeof($entityArray[$entity['subject']]);
            } else {
                $size = 0;
            }
            $entityArray[$entity['subject']][$size] = [$entity['startOrigin'], $entity['endTime']];
        }
        return $entityArray;
    }

    public function getUnitsUpgradesInConst()
    {
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT startOrigin, subject, endTime FROM game_tasks WHERE subject='soldierSpace' OR subject='workerSpace'";
        $upgradesInConstruction = $sqlQueryService->sqlQueryService($query);
        $entityArray = [];
        foreach ($upgradesInConstruction as $entity) {
            if (isset($entityArray[$entity['subject']])){
                $size = sizeof($entityArray[$entity['subject']]);
            } else {
                $size = 0;
            }
            $entityArray[$entity['subject']][$size] = ["startOrigin"=>$entity['startOrigin'], "subject"=>$entity['subject'], "endTime"=>$entity['endTime']];
        }
        return $entityArray;
    }

}