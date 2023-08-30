<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\models\Database;

class Categories extends BaseController {

	public function __construct(Database $db, Request $request, Session $session)
	{
		parent::__construct($db, $request, $session);

		$this->setValidationRules([
			[
				'name' => 'name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Category name is required.',
					'isEmpty' => 'Please enter category name.',
				],
			]
		]);
	}

	public function index() {
		$this->renderView('list', [
			'categories' => $this->db->selectAll('categories'),
		]);
	}
	
	public function new() {
		$this->renderView('add');
	}
	
	public function create() {
		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());
		
		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/categories/new');
			exit;
		}
		
		$newCategoryId = $this->db->insert('categories', ['name' => $validated['name']]);
		
		$this->setSuccessMessage('The category has been added successfully');
		
		$this->redirect('/categories/' . $newCategoryId . '/edit');
	}
	
	public function edit() {
		$categoryId = $this->request->get('id');

		// Check if id is valid.
		if (!is_numeric($categoryId)) {
			$this->setErrorMessage('Category not found.');
			$this->redirect('/categories');
			exit;
		}
		
		// Fetch category.
		$category = $this->db->selectOne('categories', ['id' => $categoryId]);
		
		// Check if product exists.
		if ($category === false) {
			$this->setErrorMessage('Category not found.');
			$this->redirect('/categories');
			exit;
		}
		
		$this->renderView('edit', [
			'category' => $category,
		]);
	}
	
	public function update() {
		$categoryId = $this->request->get('id');

		if (!$this->request->isPut()) {
			return http_response_code(400);
		}

		// Check if id is valid.
		if (!is_numeric($categoryId)) {
			$this->setErrorMessage('Category not found.');
			$this->redirect('/categories');
			exit;
		}

		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());
		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/categories/' . $categoryId . '/edit');
		}

		// Fetch product.
		$category = $this->db->selectOne('categories', ['id' => $categoryId]);
		
		// Check if product exists.
		if ($category === false) {
			$this->setErrorMessage('Category not found.');
			$this->redirect('/categories');
			exit;
		}
		
		// Update product.
		$this->db->update(
			'categories',
			[
				'name' => $validated['name']
			],
			[
				'id' => $category['id'],
			]
		);
		
		$this->setSuccessMessage('The category has been edited successfully');
		
		$this->redirect('/categories/' . $category['id'] . '/edit');
	}
	
	public function destroy() {
		$this->db->delete('categories', ['id' => $this->request->get('id') ?? 0]);
		
		$this->setSuccessMessage('The category has been deleted successfully!');
		
		$this->redirect('/categories');
	}
}
