<?php

namespace app\classes\controllers;

use app\classes\core\Request;

class Stocks extends BaseController {

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
			case 'mark_as_sold': $this->markAsSold($product['id'], $quantity); break;
		}
	}

	public function markAsSOld($productId, $quantity) {
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

		$this->setSuccessMessage('Successfully update stocks!');
		$this->redirect('/products');
	}
}
