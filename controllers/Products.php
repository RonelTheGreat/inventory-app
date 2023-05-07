<?php

class Products extends BaseController {

	public function __construct(Database $db, Request $request)
	{
		parent::__construct($db, $request);

		$this->setValidationRules([
			[
				'name' => 'name',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Product name is required.',
					'isEmpty' => 'Please enter product name.',
				],
			],
			[
				'name' => 'category',
				'type' => 'integer',
				'required' => false,
				'errorMessages' => [
					'invalidType' => 'Selected category is invalid.',
					'isEmpty' => 'Please select a category.',
					// 'greaterThanZero' => 'Please select category',
				],
			],
			[
				'name' => 'description',
				'type' => 'string',
				'required' => false,
			],

		]);
	}

	public function listGet() {
		$this->renderView('list', [
			'products' => $this->db->selectAll('products'),
		]);
	}
	
	public function addGet() {
		$this->renderView('add', [
			'categoryOptions' => $this->getCategoryOptions()
		]);
	}
	
	public function addPost() {
		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect([
				'p' => 'products',
				'action' => 'add'
			]);
			exit;
		}

		$newProductId = $this->db->insert(
			'products',
			[
				'name' => $validated['name'],
				'category_id' => $validated['category'],
				'description' => $validated['description'],
			]
		);
		
		$this->setSuccessMessage('The product has been added successfully!');
		
		$this->redirect([
			'p' => 'products',
			'action' => 'edit',
			'id' => $newProductId,
		]);
	}
	
	public function editGet() {
		$productId = $this->request->get('id');

		// Check if id is valid.
		if (!is_numeric($_GET['id'])) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $productId]);
		
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
			'categoryOptions' => $this->getCategoryOptions(),
		]);
	}
	
	public function editPost() {
		$productId = $this->request->get('id');

		// Check if id is valid.
		if (!is_numeric($productId)) {
			$this->setErrorMessage('Product not found.');
			$this->redirect([
				'p' => 'products',
				'action' => 'list',
			]);
			exit;
		}

		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect([
				'p' => 'products',
				'action' => 'edit',
				'id' => $productId
			]);
			exit;
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $productId]);
		
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
				'name' => $validated['name'],
				'category_id' => $validated['category'],
				'description' => $validated['description'],
			],
			[
				'id' => $product['id'],
			]
		);
		
		$this->setSuccessMessage('The product has been edited successfully!');
		
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

	protected function getCategoryOptions() {
		$categoryOptions = ['0' => '-- Select Category --'];
		$categories = $this->db->selectAll('categories');
		foreach ($categories as $category) {
			$categoryOptions[$category['id']] = $category['name'];
		}

		return $categoryOptions;
	}
}

