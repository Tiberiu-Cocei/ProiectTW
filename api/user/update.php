<?php
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/user/update.php
  si corpul json:
  {
    "username" : "cevaUsername",
    "parola" : "cevaParolaVALIDA",
    "nume" : "cevaNumeVALID",
    "prenume" : "cevaPrenumeVALID",
    "email" : "cevaEmailVALID"
  }
  Pentru a sti ce este valid verifica user_validation.php
  Username-ul nu conteaza daca este cu litere mari sau nu, la login face lowercase de el
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../algorithms/user_validation.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = json_decode(file_get_contents("php://input"));
if(!empty($data->username)){
  $user->username = $data->username;

  $userAux = new User($db);
  $userAux->username = $data->username;
  $userAux->getByName();

  if($userAux->username === null) {
    http_response_code(404);
    echo json_encode(array("message" => "User does not exist"));
    die();
  }

  if($data->parola !== null) $user->parola = $data->parola;
  else $user->parola = $userAux->parola;
  if($data->nume !== null) $user->nume = $data->nume;
  else $user->nume = $userAux->nume;
  if($data->prenume !== null) $user->prenume = $data->prenume;
  else $user->prenume = $userAux->prenume;
  if($data->email !== null) $user->email = $data->email;
  else $user->email = $userAux->email;

  $mesaj = isValid($user);
  if($mesaj !== "Valid") {
    http_response_code(400);
    echo json_encode(array("message" => $mesaj));
    die();
  }

  if($user->update()){
      http_response_code(204);
      echo json_encode(array("message" => "Successfully updated user info."));
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to update user info."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing username."));
}
?>
