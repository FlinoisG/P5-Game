<?php

namespace App\Service;

use App\Model\Service;
use App\Config\GameConfig;
//use App\Service\TaskService;
use App\Entity\TaskEntity;
use App\Repository\BaseRepository;
use App\Repository\TaskRepository;

class AttackService
{

    /**
     * Initiate the attack cycle with a first attack, 
     * and set the next atack task
     *
     * @param object $task
     * @return void
     */
    public function attack($task)
    {
        $gameConfig = new GameConfig;
        $baseRepository = new BaseRepository;
        $taskRepository = new TaskRepository;

        $startBuilding = explode(",", $task->getStartOrigin())[0];
        $startId = explode(",", $task->getStartOrigin())[1];
        $targetBuilding = explode(",", $task->getTargetOrigin())[0];
        $targetId = explode(",", $task->getTargetOrigin())[1];

        $targetSoldierAmount = $baseRepository->getUnits("soldier", $targetId, $targetBuilding);
        $targetHP = $baseRepository->getHP($targetId, $targetBuilding);
        $arr = explode(',', $task->getSubject());
        $attackSoldierAmount = $arr[1];

        if ((int)$targetSoldierAmount !== 0){
            if ($targetSoldierAmount >= $attackSoldierAmount){
                $negativeAmount = $attackSoldierAmount * -1;
                $baseRepository->addUnits("soldier", $targetId, $negativeAmount, $targetBuilding);
            } else {
                $newAttackSoldierAmount = $attackSoldierAmount - $targetSoldierAmount;
                $baseRepository->addUnits("soldier", $targetId, ($targetSoldierAmount * -1), $targetBuilding);
                $taskParameters = [
                    'action'=>'attack', 
                    'subject'=>"soldier,".$newAttackSoldierAmount, 
                    'startOrigin'=>$task->getStartOrigin(), 
                    'startPos'=>$task->getStartPos(),
                    'targetOrigin'=>$task->getTargetOrigin(), 
                    'targetPos'=>$task->getTargetPos(), 
                    'startTime'=>"", 
                    'endTime'=>"", 
                    'author'=>$task->getAuthor()
                ];
                $newAttackTask = new TaskEntity($taskParameters);
                $this->attackWithoutDefenses($newAttackTask);
            } 
        } else {
            $this->attackWithoutDefenses($task);
        }
    }

    /**
     * Second step of the attack cycle.
     * If soldiers in defense, negate them to the soldiers in attack.
     * If attacked building HP is below the number of attacking soldiers,
     * the building is captured.
     * If not, repeat this step in a new task
     *
     * @param [type] $task
     * @return void
     */
    public function attackWithoutDefenses($task){

        $gameConfig = new GameConfig;
        $mathService = new MathService;
        $baseRepository = new BaseRepository;
        $taskRepository = new TaskRepository;
        
        $startBuilding = explode(",", $task->getStartOrigin())[0];
        $startId = explode(",", $task->getStartOrigin())[1];
        $targetBuilding = explode(",", $task->getTargetOrigin())[0];
        $targetId = explode(",", $task->getTargetOrigin())[1];

        $targetSoldierAmount = $baseRepository->getUnits("soldier", $targetId, $targetBuilding);
        $targetHP = $baseRepository->getHP($targetId, $targetBuilding);
        $arr = explode(',', $task->getSubject());
        $attackSoldierAmount = $arr[1];
        
        if ($targetHP > $attackSoldierAmount) {
            $newTargetHP = $targetHP - $attackSoldierAmount;
            $baseRepository->setHP($newTargetHP, $targetId, $targetBuilding);
            $attackInterval = $gameConfig->getAttackInterval();
            
            $time = time() + $attackInterval;
            $taskParameters = [
                'action'=>'attack', 
                'subject'=>"soldier,".$attackSoldierAmount, 
                'startOrigin'=>$task->getStartOrigin(), 
                'startPos'=>$task->getStartPos(),
                'targetOrigin'=>$task->getTargetOrigin(), 
                'targetPos'=>$task->getTargetPos(), 
                'startTime'=>"", 
                'endTime'=>$time, 
                'author'=>$task->getAuthor()
            ];
            $newAttackTask = new TaskEntity($taskParameters);
            $taskRepository->newTask($newAttackTask);
        } else {
            //var_dump($targetBuilding);
            $soldierSpace = $baseRepository->getSpace("soldier", $targetBuilding, $targetId);
            $soldierSpace = $soldierSpace + 1;
            if ($attackSoldierAmount <= $soldierSpace){
                $baseRepository->addUnits("soldier", $targetId, $attackSoldierAmount, $targetBuilding);
            } else {
                $baseRepository->addUnits("soldier", $targetId, $soldierSpace, $targetBuilding);
                $startSoldierAmount = $attackSoldierAmount - $soldierSpace;
                $startBuilding = explode(",", $task->getStartOrigin())[0];
                $startId = explode(",", $task->getStartOrigin())[1];
                $startSoldierSpaceLeft = $baseRepository->getSpaceLeft("soldier", $startId, $startBuilding);
                if ($startSoldierSpaceLeft >= $startSoldierAmount){
                    $subject = "soldier,".$startSoldierAmount;
                } else {
                    $subject = "soldier,".$startSoldierSpaceLeft;
                    // notification : Des soldats n'ont nul part ou aller après l'attaque faute de place
                }
                //var_dump($targetBuilding);
                $duration = $mathService->calculateTravelDuration($task->getTargetPos(), $task->getStartPos(), "soldier");
                $travelTimestamp = time() + $duration;
                $taskParameters = [
                    'action'=>'move', 
                    'subject'=>$subject, 
                    'startOrigin'=>$task->getTargetOrigin(), 
                    'startPos'=>$task->getTargetPos(),
                    'targetOrigin'=>$task->getStartOrigin(), 
                    'targetPos'=>$task->getStartPos(), 
                    'startTime'=>time(), 
                    'endTime'=>$travelTimestamp, 
                    'author'=>$task->getAuthor()
                ];
                //var_dump($targetBuilding);
                $newAttackTask = new TaskEntity($taskParameters);
                $taskRepository->newTask($newAttackTask);
            }
            $newTargetHP = 100;
            if ($targetBuilding == "base"){
                $isMain = $baseRepository->getMain($targetId);
                if ($isMain = 1){
                    $newTargetHP = 500;
                };
            }
            $baseRepository->setHP($newTargetHP, $targetId, $targetBuilding);
            $baseRepository->setOwner($targetId, $targetBuilding, $task->getAuthor());
        } 
    }

}