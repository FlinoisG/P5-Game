<?php

require_once "vendor/autoload.php";

use App\Service\Auth;
use App\Service\TaskHandler;

$auth = new Auth;
$taskHandler = new TaskHandler;
$tasks = $auth->getTasks();
$taskHandler->handleTasks($tasks);
