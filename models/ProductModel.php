<?php

class ProductModel extends Database {
	
	public static function findById(int $id) {
		return self::selectOne('products', ['id' => $id]);
	}
}
