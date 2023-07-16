<?php

	require_once './config.php';

	require_once ROOT_DIR . '/models/Database.php';
	require_once ROOT_DIR . '/controllers/BaseController.php';
	require_once ROOT_DIR . '/core/Router.php';
	require_once ROOT_DIR . '/core/Request.php';
	require_once ROOT_DIR . '/core/Session.php';

	$session = new Session();
	$request = new Request();
	$db = new Database();
	$route = Router::getRoute($request);

	if (empty($route)) exit('Page not found.');

	// Authenticate admin.
	if (!isset($_COOKIE['asid'])) {
		if ($route['class'] !== 'Login') {
			header('Location: /login');
			exit;
		}
	} else {
		$decoded = explode(':', base64_decode($_COOKIE['asid']));
		$admin = $db->selectOne(
			'admins',
			[
				'id' => intval($decoded[1]),
				'session_id' => $_COOKIE['asid'],
			]
		);
		if (!$admin) {
			setcookie('asid', null);
			header('Location: /login');
			exit;
		}

		if ($route['class'] === 'Login') {
			header('Location: /products');
			exit;
		}
	}

	require_once $route['controllerFile'];

	$className = $route['class'];
	$controllerInstance = new $className($db, $request);

	$controllerInstance->setViewLayout($controllerInstance instanceof Login ? 'login' : 'default');
	$controllerInstance->setViewDirectoryName(strtolower($route['class']));

	call_user_func([$controllerInstance, $route['method']]);

