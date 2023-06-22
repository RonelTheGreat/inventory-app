<?php

class Login extends BaseController {
	public function __construct(Database $db, Request $request) {
		parent::__construct($db, $request);

		$this->setValidationRules([
			[
				'name' => 'username',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your username.',
					'isEmpty' => 'Please enter your username.',
				],
			],
			[
				'name' => 'password',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your password.',
					'isEmpty' => 'Please enter your password.',
				],
			],
		]);
	}

	public function loginGet() {
		$this->renderView('login');
	}

	public function loginPost() {
		$errorMessage = 'Username or password is incorrect.';
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect([
				'p' => 'login',
			]);
			exit;
		}

		$admin = $this->db->selectOne(
			'admins',
			[
				'username' => $validated['username'],
			]
		);

		if (!$admin) {
			$this->setErrorMessage($errorMessage);
			$this->redirect([
				'p' => 'login',
			]);
			exit;
		}

		if (!password_verify($validated['password'], $admin['password'])) {
			$this->setErrorMessage($errorMessage);
			$this->redirect([
				'p' => 'login',
			]);
			exit;
		}

		$this->redirect([
			'p' => 'products',
			'action' => 'list',
		]);
	}
}