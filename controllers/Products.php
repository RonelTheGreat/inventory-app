<?php

class Products extends BaseController {
	
	public function listGet() {
		$this->renderView('list', [
			'products' => $this->db->selectAll('products'),
		]);
	}
	
	public function addGet() {
		// Get all categories.
		$categories = $this->db->selectAll('categories');
		
		$this->renderView('add', [
			'categories' => $categories
		]);
	}
	
	public function addPost() {
		// Validate form inputs.
		$name = $_POST['name'] ?? '';
		
		if (trim($name) == '') {
			$this->setErrorMessage('Please enter product name');
			$this->redirect([
				'p' => 'products',
				'action' => 'add'
			]);
			exit;
		}
		
		$newProductId = $this->db->insert('products', ['name' => $name]);
		
		$this->setSuccessMessage('The product has been successfully added');
		
		$this->redirect([
			'p' => 'products',
			'action' => 'edit',
			'id' => $newProductId,
		]);
	}
	
	public function editGet() {
		// Check if id is valid.
		if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $_GET['id']]);
		
		// Check if product exists.
		if ($product === false) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
			exit;
		}
		
		$this->renderView('edit', [
			'product' => $product,
		]);
	}
	
	public function editPost() {
		// Check if id is valid.
		if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
			exit;
		}
		
		// Validate name field.
		if (!isset($_POST['name']) || trim($_POST['name']) == '') {
			$this->setErrorMessage('Please enter product name.');
			$this->redirect([
				'p' => 'products',
				'action' => 'edit',
				'id' => $_GET['id'],
			]);
			exit;
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $_GET['id']]);
		
		// Check if product exists.
		if ($product === false) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
			exit;
		}
		
		// Update product.
		$this->db->update(
			'products',
			[
				'name' => $_POST['name'],
			],
			[
				'id' => $product['id'],
			]
		);
		
		$this->setSuccessMessage('The product has been edited successfully.');
		
		$this->redirect([
			'p' => 'products',
			'action' => 'edit',
			'id' => $product['id'],
		]);
	}
	
	public function deleteGet() {
		$this->db->delete('products', ['id' => $_GET['id'] ?? 0]);
		
		$this->setSuccessMessage('The product has been deleted successfully!');
		
		$this->redirect([
			'p' => 'products',
			'action' => 'list',
		]);
	}
}

