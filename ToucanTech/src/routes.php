<?php
use App\Controllers\TaskController as ControllersTaskController;
use MVC\Router;


$router = new Router();
$router->addRoute('Task2', ControllersTaskController::class, 'Task2');
$router->addRoute('Task5', ControllersTaskController::class, 'Task5');
$router->addRoute('Task5/grid', ControllersTaskController::class, 'Task5Grid');
$router->addRoute('Task5/save', ControllersTaskController::class, 'Task5Save');
$router->addRoute('Task5/report', ControllersTaskController::class, 'Task5ReportData');
$uri = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING']: '/' ;
$router->dispatch($uri);
    