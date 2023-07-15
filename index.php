<?php

	require_once './config.php';

	require_once ROOT_DIR . '/models/Database.php';
	require_once ROOT_DIR . '/controllers/BaseController.php';
	require_once ROOT_DIR . '/core/Router.php';
	require_once ROOT_DIR . '/core/Request.php';

	$request = new Request();
	$route = Router::getRoute($request);

	if (empty($route)) exit('Page not found.');

	require_once $route['controllerFile'];

	$className = $route['class'];
	$controllerInstance = new $className(new Database(), $request);

	$controllerInstance->setViewLayout($controllerInstance instanceof Login ? 'login' : 'default');
	$controllerInstance->setViewDirectoryName(strtolower($route['class']));

	call_user_func([$controllerInstance, $route['method']]);

