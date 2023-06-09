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
				'name' => 'size',
				'type' => 'string',
				'required' => true,
				'errorMessages' => [
					'isRequired' => 'Size is required.',
					'isEmpty' => 'Please select a size.',
				]
			],
			[
				'name' => 'description',
				'type' => 'string',
				'required' => false,
			],
			[
				'name' => 'price',
				'type' => 'float',
				'required' => true,
				'greaterThanZero' => true,
				'errorMessages' => [
					'isRequired' => 'Product price is required.',
					'isEmpty' => 'Please enter product price.',
					'greaterThanZero' => 'Price should be greater than zero.',
				]
			],
			[
				'name' => 'stocks',
				'type' => 'integer',
				'required' => false,
			],
			[
				'name' => 'existing_images',
				'type' => 'array',
				'required' => false,
				'errorMessages' => [
					'invalidType' => 'Invalid images.',
				]
			],
			[
				'name' => 'new_images',
				'type' => 'array',
				'required' => false,
				'errorMessages' => [
					'invalidType' => 'Invalid images.',
				]
			]
		]);
	}

	public function listGet() {
		$this->renderView('list', [
			'products' => $this->db->selectAll('products'),
		]);
	}
	
	public function addGet() {
		$this->renderView('add', [
			'categoryOptions' => $this->getCategoryOptions(),
			'sizeOptions' => $this->getSizeOptions(),
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
				'price' => $validated['price'],
				'size' => $validated['size'],
				'stocks' => $validated['stocks'],
			]
		);

		// Save images.
		if (isset($validated['new_images'])) {
			foreach ($validated['new_images'] as $imageUrl) {
				$this->db->insert(
					'product_images',
					[
						'product_id' => $newProductId,
						'url' => $imageUrl,
					]
				);
			}
		}
		
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

		// Fetch images.
		$product['images'] = $this->db->selectAll('product_images', ['product_id' => $product['id']]);
		
		$this->renderView('edit', [
			'product' => $product,
			'categoryOptions' => $this->getCategoryOptions(),
			'sizeOptions' => $this->getSizeOptions(),
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
				'price' => $validated['price'],
				'size' => $validated['size'],
				'stocks' => $validated['stocks'],
			],
			[
				'id' => $product['id'],
			]
		);

		// Delete images.
		if (isset($validated['existing_images'])) {
			$existingImages = $this->db->selectAll(
				'product_images',
				[ 'product_id' => $product['id'], ],
				[ 'id', ]
			);

			$reqExistingImageIds = array_keys($validated['existing_images']);

			foreach ($existingImages as $image)
			{
				if (!in_array($image['id'], $reqExistingImageIds)) {
					$this->db->delete('product_images', [ 'id' => $image['id'], ]);
				}
			}
		}

		// Add new images.
		if (isset($validated['new_images']) && !empty($validated['new_images'])) {
			foreach ($validated['new_images'] as $imageUrl) {
				$this->db->insert(
					'product_images',
					[
						'product_id' => $product['id'],
						'url' => $imageUrl,
					]
				);
			}
		}

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

	protected function getCategoryOptions(): array {
		$categoryOptions = ['0' => '-- Select Category --'];
		$categories = $this->db->selectAll('categories');
		foreach ($categories as $category) {
			$categoryOptions[$category['id']] = $category['name'];
		}

		return $categoryOptions;
	}

	protected function getSizeOptions(): array {
		$sizes = $this->db->selectAll('sizes');

		// TODO: this should be on query level.
		uasort($sizes, fn($a, $b) => $a['order'] <=> $b['order']);

		foreach ($sizes as $size) {
			$sizeOptions[$size['code']] = $size['label'];
		}

		return $sizeOptions;
	}
}

