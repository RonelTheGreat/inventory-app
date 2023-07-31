<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\models\Database;

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

	public function index() {
		$searchParams = array_merge(
			$this->getSearchParamDefaults(),
			$this->request->get('searchParams', [])
		);

		$products = $this->getFilteredProducts($searchParams);
		foreach ($products as $key => $product) {
			$imagePreview = $this->db->selectOne('product_images', ['product_id' => intval($product['id'])]);
			if ($imagePreview !== false) {
				$products[$key]['imagePreview'] = $imagePreview['url'];
			} else {
				$products[$key]['imagePreview'] = '';
			}

			$stocks = $this->db->selectOne('stocks', ['product_id' => intval($product['id'])]);
			if ($stocks !== false) $stocks = $stocks['stocks'];
			$products[$key]['stocks'] = intval($stocks);
		}

		$this->renderView('list', [
			'products' => $products,
			'searchParams' => $searchParams,
			'comparisonOperatorOptions' => $this->getComparisonOperatorOptions(),
		]);
	}
	
	public function new() {
		$this->renderView('add', [
			'categoryOptions' => $this->getCategoryOptions(),
			'sizeOptions' => $this->getSizeOptions(),
		]);
	}
	
	public function create() {
		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());

		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/products/new');
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

		// Insert new stocks record.
		$this->db->insert(
			'stocks',
			[
				'product_id' => $newProductId,
				'stocks' => $validated['stocks'],
			],
		);
		
		$this->setSuccessMessage('The product has been added successfully!');
		
		$this->redirect('/products/' . $newProductId . '/edit');
	}
	
	public function edit() {
		$productId = $this->request->get('id');

		// Check if id is valid.
		if (!is_numeric($productId)) {
			$this->setErrorMessage('Product not found.');
			$this->redirect('/products');
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $productId]);
		
		// Check if product exists.
		if ($product === false) {
			$this->setErrorMessage('Product not found.');
			$this->redirect('/products');
			exit;
		}

		// Fetch images.
		$product['images'] = $this->db->selectAll('product_images', ['product_id' => $product['id']]);

		// Fetch stocks.
		$stocks = $this->db->selectOne('stocks', ['product_id' => $product['id']]);
		if ($stocks !== false) $stocks = $stocks['stocks'];
		$product['stocks'] = $stocks;
		
		$this->renderView('edit', [
			'product' => $product,
			'categoryOptions' => $this->getCategoryOptions(),
			'sizeOptions' => $this->getSizeOptions(),
		]);
	}
	
	public function update() {
		$productId = $this->request->get('id');

		if (!$this->request->isPut()) {
			return http_response_code(400);
		}

		// Check if id is valid.
		if (!is_numeric($productId)) {
			$this->setErrorMessage('Product not found.');
			$this->redirect('/products');
		}

		// Validate form inputs.
		$validated = $this->request->validate($this->getValidationRules());
		if (!$validated) {
			$this->setErrorMessage($this->request->getValidationErrorMessage());
			$this->redirect('/products/' . $productId . '/edit');
		}
		
		// Fetch product.
		$product = $this->db->selectOne('products', ['id' => $productId]);
		
		// Check if product exists.
		if ($product === false) {
			$this->setErrorMessage('Product not found.');
			$this->redirect('/products');
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

		// Update stocks.
		$this->db->update(
			'stocks',
			['stocks' => $validated['stocks']],
			['product_id' => intval($product['id'])],
		);

		$this->setSuccessMessage('The product has been edited successfully!');
		
		$this->redirect('/products/' . $product['id'] . '/edit');
	}
	
	public function destroy() {
		$productId = intval($this->request->get('id', 0));
		$this->db->delete('products', ['id' => $productId]);

		// Delete product images
		$this->db->delete('product_images', ['product_id' => $productId]);

		$this->setSuccessMessage('The product has been deleted successfully!');
		
		$this->redirect('/products');
	}

	private function getFilteredProducts(array $filters): array
	{
		$validComparisonOperators = ['=', '>', '>=', '<', '<='];
		$columns = ['p.*'];
		$conditions = [];
		$joins = [];

		$searchStr = trim($filters['search_str']);
		$stocks = trim($filters['stocks']);
		$price = trim($filters['price']);
		$stocksComparisonOperator = trim($filters['stocks_comparison_operator']);
		$priceComparisonOperator = trim($filters['price_comparison_operator']);

		if ($searchStr !== '') {
			$conditions[] = '(p.name LIKE "%' . $searchStr . '%" OR p.description LIKE "%' . $searchStr . '%")';
		}

		if ($stocks !== ''
			&& $stocksComparisonOperator !== ''
			&& in_array($stocksComparisonOperator, $validComparisonOperators)
		) {
			$joins[] = 'LEFT JOIN stocks as s ON s.product_id = p.id';
			$conditions[] = 's.stocks ' . $stocksComparisonOperator . ' ' . intval($stocks);
			$columns[] = 's.stocks';
		}

		if ($price !== ''
			&& $priceComparisonOperator !== ''
			&& in_array($priceComparisonOperator, $validComparisonOperators)
		) {
			$conditions[] = 'p.price ' . $priceComparisonOperator . ' ' . floatval($price);
		}

		$joinStatement = !empty($joins) ? implode(' ', $joins) : '';
		$whereStatement = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
		$query = $this->db->raw('
			SELECT ' . implode(',', $columns) . '
			FROM products AS p
			' . $joinStatement . '
			' . $whereStatement . '
		');

		return $query->fetchAll();
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

	private function getComparisonOperatorOptions(): array {
		return [
			'' => '',
			'>' => '&gt;',
			'>=' => '&ge;',
			'<' => '&lt;',
			'<=' => '&le;',
			'=' => '&equals;',
		];
	}

	private function getSearchParamDefaults(): array {
		return [
			'search_str' => '',
			'stocks' => '',
			'stocks_comparison_operator' => '',
			'price' => '',
			'price_comparison_operator' => '',
		];
	}
}

