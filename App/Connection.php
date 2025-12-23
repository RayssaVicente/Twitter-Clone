<?php

namespace App;

class Connection {

  public static function getDb() {
    try {
      // O getenv busca o nome da variável que você cadastrou na Vercel
      $host = getenv('DB_HOST') ?: "mysql-twitter-clone.alwaysdata.net";
      $dbname = getenv('DB_NAME') ?: "twitter-clone_db";
      $user = getenv('DB_USER') ?: "448174";
      $pass = getenv('DB_PASS') ?: "Z8Bi2CYdTPh@2R";

      $conn = new \PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
      );
      
      $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      return $conn;

    } catch (\PDOException $e) {
        die("Erro de conexão. Verifique as configurações de ambiente.");
    }
  }
}