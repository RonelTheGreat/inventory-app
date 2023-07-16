<?php

class Router {
	/**
	 *
	 * Controller name    | URL                    | HTTP method    | Purpose
	 *
	 * index               /products                 GET              List all products
	 * new                 /products/new             GET              Shows a form for creating a new product
	 * create              /products                 POST             Creates a new product
	 * show                /products/:id             GET              Shows the product with the given ID
	 * edit                /products/:id/edit        GET			  Shows a form for editing the product
	 * update              /products/:id             PUT              Updates the product with the given ID
	 * destroy             /products/:id             DELETE           the product with the given ID
	 *
	 */
	private static array $validRoutes = [
		'get' => [
			'/route' => 'index',
			'/route/new' => 'new',
			'/route/:id' => 'show',
			'/route/:id/edit' => 'edit',
		],
		'post' => [
			'/route' => 'create',
		],
		'put' => [
			'/route/:id' => 'update',
		],
		'delete' => [
			'/route/:id' => 'destroy',
		],
	];

	private static array $specialRoutes = [
		'get' => [
			'/login' => 'index',
		],
		'post' => [
			'/login' => 'login',
		],
	];

	public static function getRoute(Request $request) :array|bool {
		$method = $request->getMethod();
		$urlParts = parse_url($_SERVER['REQUEST_URI']);

		// Extract url parts and make a copy.
		$urlPathParts = $urlPathPartsCopy = explode('/', $urlParts['path']);

		// Check if current route is in the special route list.
		if (isset(self::$specialRoutes[$method][$urlParts['path']])) {
			$class = ucwords($urlPathParts[1]);
			// No controller defined.
			$controllerFile = ROOT_DIR . '/controllers/' . $class . '.php';
			if (!is_file($controllerFile)) return false;

			return [
				'class' => $class,
				'method' => self::$specialRoutes[$method][$urlParts['path']],
				'controllerFile' => $controllerFile,
			];
		}

		// Create mock route by changing the root route to a generic "route" word.
		// For example the route is /products/2/edit, this would become /route/2/edit.
		$urlPathPartsCopy[1] = 'route';
		$mockUrlPath = implode('/', $urlPathPartsCopy);

		$routeData = [];
		foreach (self::$validRoutes[$method] as $route => $handler) {
			$pattern = preg_replace('/:[a-zA-Z0-9_\-]+/', '([a-zA-Z0-9_\-]+)', $route);
			$pattern = str_replace('/', '\/', $pattern);
			$pattern = '/^' . $pattern . '$/';

			if (preg_match($pattern, $mockUrlPath, $matches)) {
				$class = ucwords($urlPathParts[1]);

				// No controller defined.
				$controllerFile = ROOT_DIR . '/controllers/' . $class . '.php';
				if (!is_file($controllerFile)) break;

				// Set route data.
				$routeData['class'] = $class;
				$routeData['method'] = $handler;
				$routeData['controllerFile'] = $controllerFile;

				// Extract and set dynamic parameters from the route.
				$paramNames = [];
				foreach (explode('/', $route) as $item) {
					if (str_contains($item, ':')) {
						$paramNames[] = explode(':', $item)[1];
					}
				}
				foreach ($matches as $index => $param) {
					if ($index === 0) continue;

					$key = $paramNames[$index - 1] ?? false;
					if ($key !== false) {
						$request->set($key, $param);
					}
				}

				break;
			}
		}

		return $routeData;
	}
}

