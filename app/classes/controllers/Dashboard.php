<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\InventoryLogger;
use app\classes\models\Database;

class Dashboard extends BaseController {

	public function __construct(Database $db, Request $request, Session $session)
	{
		parent::__construct($db, $request, $session);

		$this->setActiveSidebarMenu('dashboard');
	}

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
			$totalEarnings = number_format($itemsSoldQuery['total_earnings'], 2);
		}

		// Products count.
		$productsCountQuery = $this->db->raw('SELECT COUNT(*) AS `count` FROM products')->fetch();
		$productsCount = $productsCountQuery ? intval($productsCountQuery['count']) : 0;

		$this->renderView('dashboard', [
			'admin' => $admin,
			'itemsSold' => $itemsSold,
			'totalEarnings' => $totalEarnings,
			'productsCount' => $productsCount,
		]);
	}
}