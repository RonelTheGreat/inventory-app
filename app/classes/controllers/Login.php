<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\models\Database;
use DateTime;
use DateTimeZone;

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

	public function index() {
		$this->renderView('login');
	}

	public function login() {
		$errorMessage = 'Username or password is incorrect.';
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/login');
		}

		$admin = $this->db->selectOne(
			'admins',
			[
				'username' => $validated['username'],
			]
		);

		if (!$admin) {
			$this->setErrorMessage($errorMessage);
			$this->redirect('/login');
		}

		if (!password_verify($validated['password'], $admin['password'])) {
			$this->setErrorMessage($errorMessage);
			$this->redirect('/login');
		}

		$sessionId = base64_encode(time()  . ':' .  $admin['id']);
		setcookie(
			'asid',
			$sessionId,
			time() + 60 * 60 * 24 * 7, // Expires in 7 days
			'/',
			'localhost', // TODO: change this in production
			false // TODO: change this in production
		);

		$this->db->update(
			'admins',
			[
				'session_id' => $sessionId,
				'session_last_update' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s'),
			],
			[
				'id' => $admin['id'],
			]
		);

		$this->redirect('/dashboard');
	}
}