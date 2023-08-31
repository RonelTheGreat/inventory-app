<?php

namespace app\classes\controllers;

use app\classes\core\Request;
use app\classes\core\Session;
use app\classes\models\Database;

class InventoryLogs extends BaseController {

	public function __construct(Database $db, Request $request, Session $session)
	{
		parent::__construct($db, $request, $session);

		$this->setActiveSidebarMenu('inventoryLogs');
	}

	public function index() {
		$result = $this->db->raw('
			SELECT
				il.action, 
				il.qty, 
				il.from_qty, 
				il.to_qty,
				il.created_at,
				p.`name` AS product_name,
				CONCAT(a.first_name, " ", a.last_name) AS admin_fullname,
				a.username
			FROM inventory_logs AS il
			LEFT JOIN products AS p ON p.id = il.product_id
			LEFT JOIN admins AS a ON a.id = il.admin_id
			ORDER BY il.created_at DESC
		')->fetchAll();

		$logs = [];
		foreach ($result as $key => $log) {
			switch ($log['action']) {
				case 'updated product': {
					$logs[$key]['description'] = $log['admin_fullname'] . ' updated the stock of "' . $log['product_name'] . '" from ' . $log['from_qty'] . ' to ' . $log['to_qty'];
					break;
				}
				case 'added product': {
					$logs[$key]['description'] = $log['admin_fullname'] . ' add a new product "' . $log['product_name'] . '" with a stock of ' . $log['qty'];
					break;
				}
				case 'sold': {
					$logs[$key]['description'] = $log['admin_fullname'] . ' sold a quantity of ' . $log['qty'] . ' of "' . $log['product_name'] . '"';
					break;
				}
			}

			// Format date.
			$date = new \DateTime($log['created_at'], new \DateTimeZone('Asia/Manila'));
			$logs[$key]['created_at'] = $date->format('M. d, Y h:i A');
		}

		$this->renderView('list', [
			'logs' => $logs,
		]);
	}
}
