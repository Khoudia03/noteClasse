<?php

$routes = [
    '/'=> [
       'controller'=> 'noteController',
        'action'=> 'note'
    ],
    '/login'=> [
       'controller'=> 'authController',
        'action'=> 'login'
    ],
    '/logout'=> [
       'controller'=> 'authController',
        'action'=> 'logout'
    ],
];

$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

if(!isset($routes[$uri])){
    http_response_code(404);
    echo "PAGE INTROUVABLE";
    exit;
}

$controller = $routes[$uri]['controller'];
$action = $routes[$uri]['action'];

if(file_exists(dirname(__DIR__)."/controllers/$controller.php")){
    require_once dirname(__DIR__)."/controllers/$controller.php";

    if(function_exists($action)){
        $action();
    }
}
else{
    http_response_code(404);
    echo "PAGE NOT FOUND";
}