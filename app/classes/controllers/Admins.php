<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\models\Database;

class Admins Extends BaseController {

	public function __construct(Database $db, Request $request, Session $session)
	{
		parent::__construct($db, $request, $session);

		$this->setValidationRules([
			[
				'name' => 'first_name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your first name.',
					'isEmpty' => 'Please enter your first name.',
				],
			],
			[
				'name' => 'last_name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your last name.',
					'isEmpty' => 'Please enter your last name.',
				],
			],
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
			[
				'name' => 'confirm_password',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please re-type your password.',
					'isEmpty' => 'Please re-type your password.',
				],
			],
			[
				'name' => 'force_password_change',
				'type' => 'string',
				'required' => false,
			],
		]);
	}

	public function index() {
		$admins = $this->db->raw('
			SELECT * FROM admins
			WHERE id != ' . $this->getCurrentAdminId() . '
		');
		$this->renderView('list', [
			'admins' => $admins,
		]);
	}

	public function new() {
		$this->renderView('add');
	}

	public function create() {
		$this->setCustomValidationRules($this->getCustomValidationRules());
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/admins/new');
		}

		// Compare passwords.
		if ($validated['password'] !== $validated['confirm_password']) {
			$this->setErrorMessage('Passwords don\'t match.');
			$this->redirect('/admins/new');
		}

		$newAdminId = $this->db->insert('admins', [
			'first_name' => ucwords($validated['first_name']),
			'last_name' => ucwords($validated['last_name']),
			'username' => $validated['username'],
			'password' => password_hash($validated['password'], PASSWORD_BCRYPT),
			'force_password_change' => isset($validated['force_password_change']) && $validated['force_password_change'] === 'on' ? 1 : 0,
		]);

		$this->setSuccessMessage('The admin has been added successfully!');

		$this->redirect('/admins/' . $newAdminId . '/edit');
	}

	public function edit() {
		$adminId = $this->request->get('id');

		// Check if id is valid.
		if (!is_numeric($adminId)) {
			$this->setErrorMessage('Admin not found.');
			$this->redirect('/admins');
		}

		// Fetch admin.
		$admin = $this->db->selectOne('admins', ['id' => $adminId]);

		// Check if admin exists.
		if ($admin === false) {
			$this->setErrorMessage('Admin not found.');
			$this->redirect('/admins');
		}

		$this->renderView('edit', [
			'admin' => $admin,
		]);
	}

	public function update() {
		$adminId = $this->request->get('id');

		if (!$this->request->isPut()) {
			return http_response_code(400);
		}

		// Check if id is valid.
		if (!is_numeric($adminId)) {
			$this->setErrorMessage('Admin not found.');
			$this->redirect('/admins');
		}

		// Validate form inputs.
		$this->setCustomValidationRules($this->getCustomValidationRules(false));
		$validated = $this->request->validate($this->getValidationRules());
		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/products/' . $adminId . '/edit');
		}

		// Fetch admin.
		$admin = $this->db->selectOne('admins', ['id' => $adminId]);

		// Check if admin exists.
		if ($admin === false) {
			$this->setErrorMessage('Admin not found.');
			$this->redirect('/admins');
		}

		// Update admin.
		$updatedData = [
			'first_name' => ucwords($validated['first_name']),
			'last_name' => ucwords($validated['last_name']),
			'username' => $validated['username'],
			'force_password_change' => isset($validated['force_password_change']) && $validated['force_password_change'] === 'on' ? 1 : 0,
		];

		if ($validated['password'] !== '' && $validated['confirm_password'] !== '') {
			if ($validated['password'] !== $validated['confirm_password']) {
				$this->setErrorMessage('Passwords don\'t match.');
				$this->redirect('/admins/' . $admin['id'] . '/edit');
			} else {
				$updatedData['password'] = password_hash($validated['password'], PASSWORD_BCRYPT);
			}
		}

		$this->db->update(
			'admins',
			$updatedData,
			[
				'id' => $admin['id'],
			]
		);

		$this->setSuccessMessage('The admin has been edited successfully!');

		$this->redirect('/admins/' . $admin['id'] . '/edit');
	}

	private function setCustomValidationRules(array $validationRules) {
		$this->setValidationRules($validationRules);
	}

	private function getCustomValidationRules(bool $requirePassword = true): array  {
		return [
			[
				'name' => 'first_name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your first name.',
					'isEmpty' => 'Please enter your first name.',
				],
			],
			[
				'name' => 'last_name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Please enter your last name.',
					'isEmpty' => 'Please enter your last name.',
				],
			],
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
				'required' => $requirePassword,
				'errorMessages' => [
					'isRequired' => 'Please enter your password.',
					'isEmpty' => 'Please enter your password.',
				],
			],
			[
				'name' => 'confirm_password',
				'type' => 'string',
				'required' => $requirePassword,
				'errorMessages' => [
					'isRequired' => 'Please re-type your password.',
					'isEmpty' => 'Please re-type your password.',
				],
			],
			[
				'name' => 'force_password_change',
				'type' => 'string',
				'required' => false,
			],
		];
	}
}
