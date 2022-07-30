<?php 
/**
 * Classe para conexões com o banco de dados que dizem respeito à Candidatos.
 */
class CandidateGateway {

  /**
   * Conector com o banco de dados, usado para envio de queries
   */
  private $db;

  /**
   * Construtor da Classe
   * @param db Instância de DatabaseConnector
   */
  public function __construct($db) {
    $this->db = $db;
  }

  /**
   * Método que retorna todos os candidatos diretamente do banco de dados. 
   * Em caso de falha, imprime um erro na tela.
   */
  public function all() {
    $statement = 'SELECT * FROM candidatos';
    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Método que computa um voto para um dado candidato a partir de input.
   * @param input array com campos 'code' e 'tipo', contendo o código do candidato e o tipo da votação (vereador ou prefeito)
   */
  public function vote($input) {
    $statement = "UPDATE candidatos SET votos = votos + 1 WHERE Codigo = :code AND Tipo = :tipo";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        "code" => $input['code'],
        "tipo" => $input['tipo'],
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Método que reinicia a votação, setando todos os votos para zero.
   */
  public function reset_votes() {
    $statement = "UPDATE candidatos SET votos = 0";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute();
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
