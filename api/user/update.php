<?php
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
if(!empty($data->username)){
  $user->username = $data->username;

  //TODO: validare + cand lipsesc campuri + daca user-ul nu exista

  $user->parola = $data->parola;
  $user->nume = $data->nume;
  $user->prenume = $data->prenume;
  $user->email = $data->email;

  if($user->update()){
      http_response_code(200);
      echo json_encode(array("message" => "Successfully updated user info."));
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to update user info."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing or invalid data detected."));
}
?>
