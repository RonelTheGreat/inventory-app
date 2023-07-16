<?php

class Database {
	private PDO $connection;
	
	public function __construct() {
		try {
			$this->connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function insert(string $tableName, array $fields): int {
		$fieldNames = array_keys($fields);
		
		$bindFieldNames = [];
		foreach ($fieldNames as $name) $bindFieldNames[] = ':' . $name;
		
		$query = $this->connection->prepare('
			INSERT INTO ' . $tableName . '(' . implode(',', $fieldNames) . ')
			VALUES(' . implode(',', $bindFieldNames) . ')
		');
		$query->execute($fields);
		
		return $this->connection->lastInsertId();
	}
	
	public function selectOne(string $tableName, array $fields, array $selectColumns = []) {
		$selectColumnsStr = !empty($selectColumns) ? implode(',', $selectColumns) : '*';
		
		$where = [];
		foreach (array_keys($fields) as $field) $where[] = $field . ' = :' . $field;
		
		$query = $this->connection->prepare('
			SELECT ' . $selectColumnsStr . '
			FROM ' . $tableName . '
			WHERE ' . implode(' AND ', $where) . '
		');
		$query->execute($fields);
		
		return $query->fetch();
	}
	
	public function selectAll(string $tableName, array $fields = [], array $selectColumns = []): array {
		$selectColumnsStr = !empty($selectColumns) ? implode(',', $selectColumns) : '*';
		
		$where = [];
		foreach (array_keys($fields) as $field) $where[] = $field . ' = :' . $field;
		
		if (!empty($where)) {
			$query = $this->connection->prepare('
				SELECT ' . $selectColumnsStr . '
				FROM ' . $tableName . '
				WHERE ' . implode('AND ', $where) . '
			');
			$query->execute($fields);
		} else {
			$query = $this->connection->query('SELECT ' . $selectColumnsStr . 'FROM ' . $tableName);
		}
		
		$result = $query->fetchAll();
		
		return $result !== false ? $result : [];
	}
	
	public function update(string $tableName, array $fieldsToSet, array $conditions) {
		$bindFieldsToSet = [];
		foreach (array_keys($fieldsToSet) as $field) $bindFieldsToSet[] = $field . ' = :' . $field;
		
		$where = [];
		foreach (array_keys($conditions) as $field) $where[] = $field . ' = :' . $field;
		
		$query = $this->connection->prepare('
			UPDATE ' . $tableName . '
			SET ' . implode(',', $bindFieldsToSet) . '
			WHERE ' . implode('AND ', $where) . '
		');
		$query->execute(array_merge($fieldsToSet, $conditions));
	}
	
	public function delete(string $tableName, array $fields) {
		$where = [];
		foreach (array_keys($fields) as $field) $where[] = $field . ' = :' . $field;
		
		$query = $this->connection->prepare('
			DELETE FROM ' . $tableName . '
			WHERE ' . implode('AND ', $where) . '
		');
		$query->execute($fields);
	}
}

