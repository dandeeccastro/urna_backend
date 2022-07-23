<?php
class DatabaseConnector {
   private $dbConnection;

   public function _construct() {
      $host = 'localhost';
      $port = 3306;
      $database = 'eleicoes';
      $username = 'urna';
      $password = 'eleicoes2022';

      try {
         $this->dbConnection = new \PDO(
            "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$database",
            $username,
            $password
         );
      } catch (\PDOException $e) {
         exit($e->getMessage());
      }
   }

   public function get_connection() {
      return $this->dbConnection;
   }
}
