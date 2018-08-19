<?php

require_once "vendor/autoload.php";
require_once "src/Service/taskHandler.php";

//use App\Service\Auth;
use App\Service\TaskHandler;
use App\Repository\TaskRepository;

//$auth = new Auth;
$taskRepository = new TaskRepository;
$taskHandler = new TaskHandler;
$tasks = $taskRepository->getTasks();
$taskHandler->handleTasks($tasks);
