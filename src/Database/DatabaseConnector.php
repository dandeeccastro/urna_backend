<?php
/**
 * Classe de conexão com o banco de dados
 */
class DatabaseConnector {
   
   private $dbConnection;     /**< Conector do banco de dados */

   /**
    * Construtor da classe
    * Cria a conexão no banco de dados usando parâmetros previamente estabelecidos. Em caso de erro, retorna a mensagem de erro.
    */
   public function __construct() {
     $url = parse_url(getenv('CLEARDB_DATABASE_URL'))
      $host = $url['host'];
      $port = 3306;
      $database = substr($url['path'],1);
      $username = $url['user'];
      $password = $url['pass'];

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

   /**
    * Getter para dbConnection
    */
   public function get_connection() {
      return $this->dbConnection;
   }
}
