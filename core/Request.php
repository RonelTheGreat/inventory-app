<?php

class Request {
	private array $request = [];
	private string $validationErrorMessage = '';

	public function __construct(array $routeParams = []) {
		foreach ($_REQUEST as $key => $value) $this->request[$key] = $value;

		foreach ($routeParams as $key => $value) $this->request[$key] = $value;
	}

	public function set(string $key, mixed $value) {
		$this->request[$key] = $value;
	}

	public function get(string $name, string $default = null): mixed {
		return $this->request[$name] ?? $default;
	}

	private function setValidationErrorMessage(string $message) {
		$this->validationErrorMessage = $message;
	}

	public function getValidationErrorMessage(): string {
		return $this->validationErrorMessage;
	}

	public function getMethod() :string {
		$method = strtolower($_SERVER['REQUEST_METHOD']);

		// Check if request has method override and set accordingly.
		if ($method === 'post' && !is_null($this->get('method'))) {
			$method = strtolower($this->get('method'));
		}

		return $method;
	}

	public function isGet() :bool {
		return strtolower($_SERVER['REQUEST_METHOD']) === 'get';
	}

	public function isPost() :bool {
		return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
	}

	public function isPut() :bool {
		return $this->isPost() && isset($this->request['method']) && strtolower($this->request['method']) === 'put';
	}

	public function isDelete() :bool {
		return $this->isPost() && isset($this->request['method']) && strtolower($this->request['method']) === 'delete';
	}

	public function validate(array $rules): bool|array {
		// Set old request.
		$this->setOld();

		$validatedRequest = [];
		foreach ($rules as $rule) {
			$name = $rule['name'];
			$value = $this->get($name);

			// Field is required, and it's not in the request.
			if ($rule['required'] && is_null($value))
			{
				$this->setValidationErrorMessage($rule['errorMessages']['isRequired']);
				return false;
			}

			switch ($rule['type']) {
				case 'string': {
					// Not required.
					if (!$rule['required']) {
						$validatedRequest[$name] = !is_null($value) ? trim($value) : '';
						break;
					}

					// Required but given an empty string.
					$trimmedValue = trim($value);
					if ($trimmedValue == '') {
						$this->setValidationErrorMessage($rule['errorMessages']['isEmpty']);
						return false;
					}

					$validatedRequest[$name] = $trimmedValue;
					break;
				}

				case 'integer':
				case 'float': {
					$trimmedValue = trim($value);

					// Not required. Always set value to zero if not a valid number.
					if (!$rule['required']) {
						$newValue = 0;
						if (is_numeric($trimmedValue)) {
							$newValue = $rule['type'] == 'integer' ? intval($trimmedValue) : floatval($trimmedValue);
						}

						$validatedRequest[$name] = $newValue;
						break;
					}

					// Value is empty (i.e. "")
					if ($trimmedValue == '') {
						$this->setValidationErrorMessage($rule['errorMessages']['isEmpty']);
						return false;
					}

					// Value is invalid (e.g. '123a', 'xyz', etc.).
					if (!is_numeric($trimmedValue)) {
						$this->setValidationErrorMessage($rule['errorMessages']['invalidType']);
						return false;
					}

					// Value should be greater than zero.
					$newValue = $rule['type'] == 'integer' ? intval($trimmedValue) : floatval($trimmedValue);
					if (isset($rule['greaterThanZero']) && $newValue <= 0) {
						$this->setValidationErrorMessage($rule['errorMessages']['greaterThanZero']);
						return false;
					}

					$validatedRequest[$name] = $newValue;
					break;
				}

				case 'array': {
					// Not a valid array.
					if ($rule['required'] && !is_array($value)) {
						$this->setValidationErrorMessage($rule['errorMessages']['invalidType']);
						return false;
					}

					$values = is_array($value) ? $value: [];
					$items = [];
					foreach ($values as $key => $item) {
						// Sanitize.
						$items[$key] = trim($item);
					}
					$validatedRequest[$name] = $items;

					break;
				}
			}
		}

		return $validatedRequest;
	}

	public function setOld() {
		foreach ($_REQUEST as $key => $value) {
			// Skip password field.
			if ($key == 'password') continue;

			$_SESSION['old'][$key] = $value;
		}
	}

	public function getOld() {
		$requestOld = [];
		if (isset($_SESSION['old'])) {
			foreach ($_SESSION['old'] as $key => $value) {
				$requestOld[$key] = $value;
			}

			unset($_SESSION['old']);
		}

		return $requestOld;
	}
}