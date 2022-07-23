<?php
include_once __DIR__ . '/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// echo $_SERVER['REQUEST_URI'];
// echo $_SERVER['REQUEST_METHOD'];

$method = strval($_SERVER['REQUEST_METHOD']);
$uri = strval($_SERVER['REQUEST_URI']);

$dbConnection = (new DatabaseConnector())->get_connection();
$controller = new CandidateController($dbConnection, $method, $uri);
$controller->parse_request();
?>
