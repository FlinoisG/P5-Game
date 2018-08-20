<?php

require_once "vendor/autoload.php";
require_once "src/Service/taskService.php";

use App\Service\TaskService;
use App\Repository\TaskRepository;

$taskRepository = new TaskRepository;
$taskService = new TaskService;
$tasks = $taskRepository->getTasks();
$taskService->handleTasks($tasks);
