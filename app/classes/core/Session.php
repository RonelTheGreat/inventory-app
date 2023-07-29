<?php

namespace app\classes\core;

class Session {

	public function __construct() {
		session_start();
	}
}
