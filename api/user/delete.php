<?php
//fisierul asta este pentru testare; nu ar trebui sa fie posibila stergerea asta
// returneaza 503 daca este folosit ca foreign key deja (irelevant ^)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->username = $data->username;

$user->getByName();
if($user->parola === null) {
  http_response_code(404);
  echo json_encode(array("message" => "User does not exist."));
}
else {
  if($user->delete()){
      http_response_code(204);
      echo json_encode(array("message" => "User was deleted."));
  } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to delete user."));
  }
}
?>
