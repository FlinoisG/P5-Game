<?php

require_once "../vendor/autoload.php";

use App\Controller\DefaultController;
use App\Controller\testController;



if (isset($_GET['p'])) {
    $routeTemp = explode('.', $_GET['p']);
    if (count($routeTemp) === 2) {
        $routeTemp = [
            "controller" => $routeTemp[0],
            "action" => $routeTemp[1]
        ];
    }
} else {
    $routeTemp = [
        "controller" => "home",
        "action" => "home"
    ];
}

if (sizeOf($routeTemp) == 1) {
    $controller = $routeTemp[0];
    $routeTemp = [
        "controller" => $controller,
        "action" => $controller
    ];
};

$controller = "\\App\\Controller\\" . ucfirst($routeTemp['controller']) . "Controller";
if (class_exists($controller, true)) {
    $controller = new $controller();
    if (in_array($routeTemp["action"], get_class_methods($controller))) {
        call_user_func([$controller, $routeTemp["action"]]);
    } else {
        $controller->error('404');
    }
} else {
    $controller = new DefaultController();
    $controller->error('404');
}
