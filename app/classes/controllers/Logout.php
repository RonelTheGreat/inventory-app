<?php

namespace app\classes\controllers;

class Logout extends BaseController {

	public function index() {
		setcookie('asid', null);

		$this->redirect('/login');
	}
}
