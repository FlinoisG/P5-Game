<?php

require_once "../vendor/autoload.php";
require_once "../src/lib/password.php";

use App\Controller\DefaultController;
use App\Controller\testController;

// Le router prends le paramètre get "p" et le coupe en deux au niveau du point.
// la première partie sera le controller et la seconde l'action du controller.
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

// Si $_GET['p'] ne contiens qu'un controlleur et pas d'action, 
// l'action utilisée sera une action "par défaut" du controlleur qui
// portera le même nom que ce dernier.
if (sizeOf($routeTemp) == 1) {
    $controller = $routeTemp[0];
    $routeTemp = [
        "controller" => $controller,
        "action" => $controller
    ];
};

//
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