<?php

class Categories extends BaseController {
	
	public function listGet() {
		$this->renderView('list', [
			'categories' => $this->db->selectAll('categories'),
		]);
	}
	
	public function addGet() {
		$this->renderView('add');
	}
	
	public function addPost() {
		// Validate form inputs.
		$name = $_POST['name'] ?? '';
		
		if (trim($name) == '') {
			$this->setErrorMessage('Please enter category name');
			$this->redirect([
				'p' => 'categories',
				'action' => 'add'
			]);
			exit;
		}
		
		$newCategoryId = $this->db->insert('categories', ['name' => $name]);
		
		$this->setSuccessMessage('The category has been added successfully');
		
		$this->redirect([
			'p' => 'categories',
			'action' => 'edit',
			'id' => $newCategoryId,
		]);
	}
	
	public function editGet() {
		// Check if id is valid.
		if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			$this->setErrorMessage('Category not found.');
			$this->redirect([
				'p' => 'categories',
				'action' => 'list',
			]);
			exit;
		}
		
		// Fetch category.
		$category = $this->db->selectOne('categories', ['id' => $_GET['id']]);
		
		// Check if product exists.
		if ($category === false) {
			$this->setErrorMessage('Category not found.');
			$this->redirect([
				'p' => 'categories',
				'action' => 'list',
			]);
			exit;
		}
		
		$this->renderView('edit', [
			'category' => $category,
		]);
	}
	
	public function editPost() {
		// Check if id is valid.
		if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			$this->setErrorMessage('Category not found.');
			$this->redirect([
				'p' => 'categories',
				'action' => 'list',
			]);
			exit;
		}
		
		// Validate name field.
		if (!isset($_POST['name']) || trim($_POST['name']) == '') {
			$this->setErrorMessage('Please enter category name.');
			$this->redirect([
				'p' => 'categories',
				'action' => 'edit',
				'id' => $_GET['id'],
			]);
			exit;
		}
		
		// Fetch product.
		$category = $this->db->selectOne('categories', ['id' => $_GET['id']]);
		
		// Check if product exists.
		if ($category === false) {
			$this->setErrorMessage('Category not found.');
			$this->redirect([
				'p' => 'categories',
				'action' => 'list',
			]);
			exit;
		}
		
		// Update product.
		$this->db->update(
			'categories',
			[
				'name' => $_POST['name']
			],
			[
				'id' => $category['id']
			]
		);
		
		$this->setSuccessMessage('The category has been edited successfully');
		
		$this->redirect([
			'p' => 'categories',
			'action' => 'edit',
			'id' => $category['id'],
		]);
	}
	
	public function deleteGet() {
		$this->db->delete('categories', ['id' => $_GET['id'] ?? 0]);
		
		$this->setSuccessMessage('The category has been deleted successfully!');
		
		$this->redirect([
			'p' => 'categories',
			'action' => 'list',
		]);
	}
}