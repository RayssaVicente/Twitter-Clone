<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8mb4;",
				"root",
				"0612" 
			);
			 $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			return $conn;

		} catch (\PDOException $e) {
            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
	}
}

?>