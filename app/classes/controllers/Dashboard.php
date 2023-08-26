<?php

namespace app\classes\controllers;

use app\classes\InventoryLogger;

class Dashboard extends BaseController {

	public function index() {
		$admin = $this->db->selectOne('admins', ['id' => $this->getCurrentAdminId()]);

		// Items sold.
		$itemsSoldQuery = $this->db->raw('
			SELECT SUM(qty) AS sold, SUM(price * qty) AS total_earnings
			FROM inventory_logs
			WHERE action = "' . InventoryLogger::ACTION_SOLD . '"
		')->fetch();

		$itemsSold = 0;
		$totalEarnings = 0;
		if ($itemsSoldQuery !== false)
		{
			$itemsSold = intval($itemsSoldQuery['sold']);
			$totalEarnings = floatval($itemsSoldQuery['total_earnings']);
		}

		// Products count.
		$productsCountQuery = $this->db->raw('SELECT COUNT(*) AS `count` FROM products')->fetch();
		$productsCount = $productsCountQuery ? intval($productsCountQuery['count']) : 0;

		$this->renderView('dashboard', [
			'admin' => $admin['username'],
			'itemsSold' => $itemsSold,
			'totalEarnings' => $totalEarnings,
			'productsCount' => $productsCount,
		]);
	}
}