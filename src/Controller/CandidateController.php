<?php 
/**
 * Classe de controle da entidade de Candidato
 */
class CandidateController {
  /**
   * Conexão com o banco de dados
   */
  private $db;

  /**
   * Método da requisição recebida pela controller
   */
  private $method;

  /**
   * URI da requisição recebida pela controller
   */
  private $uri;

  /**
   * Instância de CandidateGateway
   */
  private $gateway;

  /**
   * Construtor da classe
   * @param db Conector do banco de dados
   * @param method Método usado na requisição à API
   * @param uri URI da requisição à API
   */
  public function __construct($db, $method, $uri) {
    $this->db = $db;
    $this->method = $method;
    $this->uri = $uri;

    $this->gateway = new CandidateGateway($db);
  }

  /**
   * Método de parsear a requisição à API
   * Verifica o método recebido e a URI, e caso a rota esteja definida, a resposta é gerada com o método necessário.
   * Em caso da rota não existir, retorna 404
   */
  public function parse_request() {
    switch ($this->method) {
    case 'GET':
      if (strcmp($this->uri,'/etapas') == 0) $response = $this->get_etapas();
      else if (strcmp($this->uri,'/resultados') == 0) $response = $this->results();
      else $response = $this->error('404', 'Not Found');
      break;

    case 'POST':
      if (strcmp($this->uri,'/vote/vereador') == 0) $response = $this->vote();
      else if (strcmp($this->uri,'/vote/prefeito') == 0) $response = $this->vote();
      else if (strcmp($this->uri,'/reset') == 0) $response = $this->reset_votes();
      else $response = $this->error('404', 'Not Found');
      break;

    default:
      $response = $this->error('404', 'Not Found');
      break;
    }

    header($response['status']);
    if ($response['body'])
      echo $response['body'];
  }

  /**
   * Método para pegar as etapas da eleição
   * Pega os candidatos do banco de dados usando o gateway e monta a resposta da requisição
   */
  private function get_etapas() {
    $result = $this->gateway->all();
    $result = $this->format_etapas($result);
    $response['status'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  /**
   * Método para computar um voto na eleição
   * Usa o corpo da requisição POST para pegar os parâmetros, define os códigos reservados para votos nulos e brancos quando necessário e 
   * envia o voto usando o gateway, retornando um código de resultado
   */
  private function vote() {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if ($input['code'] === null) $input['code'] = "999";
    else if ($input['code'] == '') $input['code'] = "0";

    error_log(json_encode($input));

    $this->gateway->vote($input);
    $response['status'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('result' => 'Vote computed'));
    return $response;
  }

  /**
   * Método para pegar os resultados da eleição
   * Pega os resultados do BD usando o gateway e monta a resposta da requisição
   */
  private function results() {
    $result = $this->gateway->all();
    $result = $this->format_results($result);
    $response['status'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  /**
   * Método para gerar códigos de erro
   * @param code Código de erro do HTTP
   * @param message Mensagem de erro
   */
  private function error($code, $message) {
    $response['status'] = "HTTP/1.1 $code $message";
    $response['body'] = null;
    return $response;
  }

  /**
   * Método para formatar resultados da eleição no JSON de etapas
   * @param candidates Candidatos retornados do gateway
   * Formata os resultados para um array com o formato necessário para as eleições no frontend
   */
  private function format_etapas($candidates) {
    $result = array(
      "0" => array(
        'titulo' => 'vereador',
        'numeros' => 5,
        'candidatos' => array(),
      ),
      "1" => array(
        'titulo' => 'prefeito',
        'numeros' => 2,
        'candidatos' => array(),
      )
    );
    foreach ($candidates as $candidate) {
      if ($candidate['Tipo'] == 'vereador') {
        $result[0]['candidatos'][$candidate['Codigo']] = array(
          "nome" => $candidate['Nome'],
          "partido" => $candidate['Partido'],
          "foto" => $candidate['Foto'],
        );
      } 
      else if ($candidate['Tipo'] == 'prefeito') {
        $result[1]['candidatos'][$candidate['Codigo']] = array(
          "nome" => $candidate['Nome'],
          "partido" => $candidate['Partido'],
          "foto" => $candidate['Foto'],
          "vice" => array(
            "nome" => $candidate['Vice_Nome'],
            "partido" => $candidate['Vice_Partido'],
            "foto" => $candidate['Vice_Foto'],
          )
        );
      }
    }

    return $result;
  }

  /**
   * Método para gerar resutlados da eleição
   * @param candidates Candidatos retornados do gateway
   * Formata o resultado para ser depois usado na tabela de resultados
   */
  private function format_results($candidates) {
    $result = array();
    foreach ($candidates as $candidate) {
      $entry = array(
        "nome" => $candidate['Nome'],
        "tipo" => $candidate['Tipo'],
        "codigo" => $candidate['Codigo'],
        "partido" => $candidate['Partido'],
        "votos" => $candidate['Votos'],
        "vice_nome" => $candidate['Vice_Nome'],
        "vice_partido" => $candidate['Vice_Partido'],
      );
      array_push($result, $entry);
    }
    return $result;
  }

  private function reset_votes() {
    $result = $this->gateway->reset_votes();
    $response['status'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }
}
