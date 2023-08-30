<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\models\Database;

class BaseController {
	private string $layoutDirectory;
	private string $viewsDirectory;

	protected array $validationRules;
	
	protected Database $db;
	protected Request $request;
	protected Session $session;
	
	public function __construct(Database $db, Request $request, Session $session) {
		$this->db = $db;
		$this->request = $request;
		$this->session = $session;
	}
	
	public function setViewDirectoryName(string $viewDirectoryName) {
		$this->viewsDirectory = ROOT_DIR . '/app/views/' . $viewDirectoryName;
	}
	
	public function setViewLayout(string $layoutName) {
		$this->layoutDirectory = ROOT_DIR . '/app/views/layouts/' . $layoutName . '.php';
	}
	
	public function getViewLayout(): string {
		return $this->layoutDirectory;
	}
	
	public function getHandler(string $action, string $method): string {
		return strtolower($action) . ucwords(strtolower($method));
	}
	
	protected function renderView(string $viewName, array $data = []) {
		$errorMessage = $this->getErrorMessage();
		$successMessage = $this->getSuccessMessage();

		$request['old'] = $this->request->getOld();

		extract($data, EXTR_OVERWRITE);
		
		$view = $this->viewsDirectory . '/' . $viewName . '.php';
		
		include_once $this->getViewLayout();
	}
	
	protected function redirect(string $url, array $queryParams = []) {
		$finalParams = [];
		foreach ($queryParams as $name => $value) $finalParams[] = $name . '=' . $value;
		
		header('Location: ' . HTTPS_URL . $url . implode('&', $finalParams));
		exit;
	}

	protected function setErrorMessage(string $errorMessage) {
		$_SESSION['errorMessage'] = $errorMessage;
	}
	
	protected function getErrorMessage(): string {
		$errorMessage = $_SESSION['errorMessage'] ?? '';
		
		unset($_SESSION['errorMessage']);
		
		return $errorMessage;
	}
	
	protected function setSuccessMessage(string $successMessage) {
		$_SESSION['successMessage'] = $successMessage;
	}
	
	protected function getSuccessMessage(): string {
		$errorMessage = $_SESSION['successMessage'] ?? '';
		
		unset($_SESSION['successMessage']);
		
		return $errorMessage;
	}

	protected function setValidationRules(array $rules) {
		$this->validationRules = $rules;
	}

	protected function getValidationRules(): array {
		return $this->validationRules;
	}

	protected function getCurrentAdminId(): int {
		$decoded = explode(':', base64_decode($_COOKIE['asid']));
		return intval($decoded[1]);
	}
}

