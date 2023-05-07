<?php

require_once './config.php';

require_once ROOT_DIR . '/models/Database.php';
require_once ROOT_DIR . '/controllers/BaseController.php';
require_once ROOT_DIR . '/controllers/Router.php';
require_once ROOT_DIR . '/core/Request.php';

$router = new Router();
$router->setRoutes(ROOT_DIR . '/routes.php');
$controllerFile = $router->getRoute( $_REQUEST['p'] ?? '', $_REQUEST['action'] ?? '');

if ($controllerFile === false) exit('Page not found.');

require_once  $controllerFile;

$className = ucwords($router->getPage());
$controllerInstance = new $className(new Database(), new Request());

$controllerInstance->setViewLayout('default');
$controllerInstance->setViewDirectoryName($router->getPage());
$handler = $controllerInstance->getHandler($router->getAction(), $_SERVER['REQUEST_METHOD']);
call_user_func([$controllerInstance, $handler]);

