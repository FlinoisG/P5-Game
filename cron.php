<?php

require_once "vendor/autoload.php";
//require_once "src/Service/taskService.php";

use App\Service\TaskService;
use App\Repository\TaskRepository;
use App\Service\MiningService;

$taskRepository = new TaskRepository;
$taskService = new TaskService;
$miningService = new MiningService;

$tasks = $taskRepository->getTasks();
$taskService->handleTasks($tasks);

$miningService->miningCycle();

