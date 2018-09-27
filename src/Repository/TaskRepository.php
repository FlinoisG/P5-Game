<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\TaskEntity;
use App\Service\sqlQueryService;

class TaskRepository extends Repository
{

    /**
     * Insert new task in database
     *
     * @param object TaskEntity
     * @return void
     */
    public function newTask($task)
    {
        $DBConnection = $this->getDBConnection();
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
        if ($task->getStartTime() == null){
            $startTime = time();
        } else {
            $startTime = $task->getStartTime();
        }
        if (gettype($task->getStartPos()) == 'array'){
            $startPos = "[".$task->getStartPos()[0].",".$task->getStartPos()[1]."]";
        } else {
            $startPos = $task->getStartPos();
        }
        if (gettype($task->getTargetPos()) == 'array'){
            $targetPos = "[".$task->getTargetPos()[0].",".$task->getTargetPos()[1]."]";
        } else {
            $targetPos = $task->getTargetPos();
        }
        $action = $task->getAction();
        $subject = $task->getSubject();
        $startOrigin = $task->getStartOrigin();
        $targetOrigin = $task->getTargetOrigin();
        $endTime = $task->getEndTime();
        $author = $task->getAuthor();
        $query->bindParam(":action", $action, PDO::PARAM_STR);
        $query->bindParam(":subject", $subject, PDO::PARAM_STR);
        $query->bindParam(":startOrigin", $startOrigin, PDO::PARAM_STR);
        $query->bindParam(":startPos", $startPos, PDO::PARAM_STR);
        $query->bindParam(":targetOrigin", $targetOrigin, PDO::PARAM_STR);
        $query->bindParam(":targetPos", $targetPos, PDO::PARAM_STR);
        $query->bindParam(":startTime", $startTime, PDO::PARAM_INT);
        $query->bindParam(":endTime", $endTime, PDO::PARAM_INT);
        $query->bindParam(":author", $author, PDO::PARAM_STR);

        $query->execute();
    }

    /**
     * Remove a task from database with its id
     *
     * @param mixed $taskId Id of the task to remove
     * @return void
     */
    public function removeTask($taskId)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare('DELETE FROM game_tasks WHERE id = :taskId');
        $query->bindParam(":taskId", $taskId, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Get all tasks from database. can specified a filtre for actions.
     *
     * @param string $action Filter 
     * @return void
     */
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
            //var_dump($tasks);
        }
        $taskEntities = [];
        for ($i=0; $i < sizeof($tasks); $i++) { 
            $taskParameters = [
                'id'=>$tasks[$i]["id"], 
                'action'=>$tasks[$i]["action"], 
                'subject'=>$tasks[$i]["subject"], 
                'startOrigin'=>$tasks[$i]["startOrigin"], 
                'startPos'=>$tasks[$i]["startPos"],
                'targetOrigin'=>$tasks[$i]["targetOrigin"], 
                'targetPos'=>$tasks[$i]["targetPos"], 
                'startTime'=>$tasks[$i]["startTime"], 
                'endTime'=>$tasks[$i]["endTime"], 
                'author'=>$tasks[$i]["author"]
            ];
            $taskEntities[$i] = new TaskEntity($taskParameters);
        }
        //var_dump($taskEntities);
        return $taskEntities;
    }

    /**
     * check entities in construction inside a specified building
     *
     * @param string $subject
     * @param string $baseType
     * @param mixed $baseId
     * @return string
     */
    public function getEntityInConst($subject, $baseType, $baseId)
    {
        $baseOrigin = $baseType . "," . $baseId;
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT endTime FROM game_tasks WHERE startOrigin = :baseOrigin AND subject = :subject");
        $query->bindParam(":baseOrigin", $baseOrigin, PDO::PARAM_STR);
        $query->bindParam(":subject", $subject, PDO::PARAM_STR);
        $query->execute();
        $subjectInConstruct = $query->fetch();
        return $subjectInConstruct;
    }

    /**
     * Returns subject, startOrigin and endTime from 
     * unit construction task in the database
     *
     * @return array
     */
    public function getAllUnitInConst()
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

    /**
     * Returns subject, startOrigin and endTime from 
     * space upgrade construction task in the database
     *
     * @return array
     */
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

    /**
     * Update the number of soldier in an attack task.
     *
     * @param int $newSoldierAmount
     * @param int $taskId
     * @return void
     */
    public function setAttackSoldiers($newSoldierAmount, $taskId)
    {
        $DBConnection = $this->getDBConnection();
        $subject = "soldier," . $newSoldierAmount;
        $statement = "UPDATE game_tasks SET subject = :sub WHERE id= :id";
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':sub', $subject, PDO::PARAM_STR);
        $query->bindParam(':id', $taskId, PDO::PARAM_INT);
        $query->execute();
    }

    public function deleteEverything()
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("DELETE FROM game_tasks");
        $query->execute();
    }

}