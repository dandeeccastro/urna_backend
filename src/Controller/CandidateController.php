<?php 
class CandidateController {
   private $db;
   private $method;
   private $uri;

   private $gateway;

   public function __construct($db, $method, $uri) {
      $this->db = $db;
      $this->method = $method;
      $this->uri = $uri;

      $this->gateway = new CandidateGateway($db);
   }

   public function parse_request() {
      switch ($this->method) {
         case 'GET':
            if (strcmp($this->uri,'/etapas') >= 0) $response = $this->get_etapas();
            else $response = $this->error('404', 'Not Found');
            break;

         case 'POST':
            if (strcmp($this->uri,'/vote/vereador') >= 0) $response = $this->vote();
            else if (strcmp($this->uri,'/vote/presidente') >= 0) $response = $this->vote();
            else $response = $this->error('404', 'Not Found');
            break;

         default:
            $response['status'] = 'HTTP/1.1 200 OK';
            $response['body'] = "$this->method asfhsuadfhusadfhuashdf";
            break;
      }

      header($response['status']);
      if ($response['body'])
         echo $response['body'];
   }

   private function get_etapas() {
      // $result = $this->gateway->all();
      $result = json_encode(array(['code' => 12345]));
      $response['status'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
   }

   private function vote() {
      $input = (array) json_decode(file_get_contents('php://input'), TRUE);
      $this->gateway->add_vote($input['code']);
      $response['status'] = 'HTTP/1.1 200 OK';
      $response['body'] = null;
      return $response;
   }

   private function error($code, $message) {
      $response['status'] = "HTTP/1.1 $code $message";
      $response['body'] = null;
      return $response;
   }
}
