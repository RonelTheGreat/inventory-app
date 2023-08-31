<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\InventoryLogger;
use app\classes\models\Database;

class Stocks extends BaseController {

	private InventoryLogger $logger;

	public function __construct(Database $db, Request $request, Session $session)
	{
		parent::__construct($db, $request, $session);

		$this->logger = new InventoryLogger($db, $this->getCurrentAdminId());
	}

	public function update() {
		$mode = $this->request->get('mode');
		$productId = intval($this->request->get('id', 0));
		$quantity = intval($this->request->get('quantity', 0));

		if (is_null($mode) || $productId <= 0 || $quantity <= 0) {
			$this->setErrorMessage('Cannot update stocks, please try again.');
			$this->redirect('/products');
		}

		$product = $this->db->selectOne('products', ['id' => $productId]);
		if ($product === false) {
			$this->setErrorMessage('Product not found.');
			$this->redirect('/products');
		}

		switch ($mode)
		{
			case 'mark_as_sold': $this->markAsSold($product['id'], $quantity, $product['price']); break;
		}
	}

	public function markAsSOld(int $productId, int $quantity, float $price) {
		$result = $this->db->selectOne('stocks', ['product_id' => $productId]);
		if ($result === false || $result['stocks'] <= 0) {
			$this->setErrorMessage('Cannot update stocks, please try again.');
			$this->redirect('/products');
		}

		$newQuantity = intval($result['stocks']) - $quantity;
		if ($newQuantity < 0) $newQuantity = 0;

		$this->db->update(
			'stocks',
			['stocks' => $newQuantity],
			['id' => intval($result['id'])]
		);

		// Insert inventory log.
		$this->logger->log(
			$productId,
			InventoryLogger::ACTION_SOLD,
			$price,
			intval($result['stocks']),
			$newQuantity
		);

		$this->setSuccessMessage('Successfully update stocks!');
		$this->redirect('/products');
	}
}
