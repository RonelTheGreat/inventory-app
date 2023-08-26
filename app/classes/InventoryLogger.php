<?php

namespace app\classes;

use app\classes\models\Database;

class InventoryLogger
{
	const ACTION_SOLD = 'sold';
	const ACTION_ADDED_PRODUCT = 'added product';
	const ACTION_UPDATED_PRODUCT = 'updated product';

	private Database $db;

	private int $adminId = 0;

	public function __construct(Database $db, int $adminId) {
		$this->db = $db;
		$this->adminId = $adminId;
	}

	public function log(int $productId, string $action, float $price, int $oldQuantity, int $newQuantity) {
		$this->db->insert('inventory_logs', [
			'admin_id' => $this->adminId,
			'product_id' => $productId,
			'action' => $action,
			'price' => $price,
			'qty' => $action === self::ACTION_SOLD ? $oldQuantity - $newQuantity : $newQuantity,
			'from_qty' => $oldQuantity,
			'to_qty' => $newQuantity,
		]);
	}
}
