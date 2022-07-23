<?php 
class CandidateGateway {
   private $db;

   public function _construct($db) {
      $this->db = $db;
   }

   public function all() {
      $statement = 'SELECT codigo, nome, partido, foto FROM candidatos';
      try {
         $statement = $this->db->query($statement);
         $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
         return $result;
      } catch (\PDOException $e) {
         exit($e->getMessage());
      }
   }

   public function vote($code) {
      $statement = "UPDATE candidatos SET votos = votos + 1 WHERE code = $code";
      try {
         $statement = $this->db->prepare($statement);
         $statement->execute();
         return $statement->rowCount();
      } catch (\PDOException $e) {
         exit($e->getMessage());
      }
   }
}
