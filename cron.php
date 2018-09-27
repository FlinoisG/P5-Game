<?php

require_once "vendor/autoload.php";

use App\Repository\TaskRepository;
use App\Service\TaskService;
use App\Service\MiningService;
use App\Service\GameManagerService;

echo    
"//////////////////////////////////
//// Cron " . date("d/m/Y H:i:s") . "
//////////////////////////////////\n";

$taskRepository = new TaskRepository;
$taskService = new TaskService;
$miningService = new MiningService;
$gameManagerService = new GameManagerService;

$file = file_get_contents(__DIR__.'/deposit/ResetDate.json');
$resetDate = json_decode($file, true);

if ($resetDate < time()){
    $gameManagerService->createNewGame();
    $endOfWeek = strtotime('next Sunday', time()) + 86400;
    $fp = fopen('deposit/ResetDate.json', 'w');
    fwrite($fp, $endOfWeek);
    fclose($fp);
} else {
    $tasks = $taskRepository->getTasks();
    $taskService->handleTasks($tasks);

    $miningService->miningCycle();
};