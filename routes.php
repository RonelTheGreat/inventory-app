<?php

const CONTROLLER_DIR = ROOT_DIR . '/controllers';

return [
	'products' => [
		'actions' => ['list', 'add', 'edit', 'delete'],
		'handler' => CONTROLLER_DIR . '/Products.php',
	],
	'categories' => [
		'actions' => ['list', 'add', 'edit', 'delete'],
		'handler' => CONTROLLER_DIR . '/Categories.php',
	],
	'login' => [
		'actions' => ['login'],
		'handler' => CONTROLLER_DIR . '/Login.php',
	]
];

