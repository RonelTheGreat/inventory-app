<?php

class Router {
	protected array $routes;
	private string $page;
	private string $action;
	
	public function getRoutes(): array {
		return $this->routes;
	}
	
	public function setRoutes(string $routesDirectory) {
		$this->routes = require_once $routesDirectory;
	}
	
	public function getRoute(string $page, string $action): bool|string {
		// No "p" (page) query param.
		if (trim($page) == '') return false;
		
		// Has "p" query param but not in the list of valid routes.
		if (!in_array($page, array_keys($this->getRoutes()))) return false;
		
		// No action indicated.
		if (trim($action) == '') return false;
		
		// Has action indicated but not in the list of valid actions per page.
		if (!in_array($action, $this->getRoutes()[$page]['actions'])) return false;
		
		$controllerFile = $this->getRoutes()[$page]['handler'];
		
		if (!file_exists($controllerFile)) return false;
		
		$this->page = $page;
		$this->action = $action;
		
		return $controllerFile;
	}
	
	public function getPage(): string {
		return $this->page;
	}
	
	public function getAction(): string {
		return $this->action;
	}
}
