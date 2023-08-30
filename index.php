<?php

	use app\classes\controllers\Login;
	use app\classes\core\Request;
	use app\classes\core\Router;
	use app\classes\core\Session;
	use app\classes\models\Database;

	require_once 'vendor/autoload.php';
	require_once './config.php';

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

		$sessionDate = new DateTime($admin['session_last_update'], new DateTimeZone('Asia/Manila'));
		$dateNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
		if ($dateNow->getTimestamp() - $sessionDate->getTimestamp() >= ADMIN_SESSION_TIMEOUT_IN_SECONDS) {
			setcookie('asid', null);
			header('Location: /login');
			exit;
		}

		$db->update(
			'admins',
			[
				'session_last_update' => $dateNow->format('Y-m-d H:i:s'),
			],
			[
				'id' => $admin['id'],
			],
		);

		if ($route['class'] === 'Login') {
			header('Location: /products');
			exit;
		}
	}

	require_once $route['controllerFile'];

	$className = '\\app\\classes\\controllers\\' . $route['class'];
	$controllerInstance = new $className($db, $request, $session);

	$controllerInstance->setViewLayout($controllerInstance instanceof Login ? 'login' : 'default');
	$controllerInstance->setViewDirectoryName(strtolower($route['class']));

	call_user_func([$controllerInstance, $route['method']]);

