<?php

namespace app\classes\core;

class Session {

	public function __construct() {
		session_start();
	}

	public function set(string $name, mixed $value) {
		$_SESSION[$name] = $value;
	}

	public function get(string $name, mixed $default = null): mixed {
		return $_SESSION[$name] ?? $default;
	}
}
