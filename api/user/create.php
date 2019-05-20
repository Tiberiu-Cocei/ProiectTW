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

//the data from POST
$data = json_decode(file_get_contents("php://input"));

if( //TODO: VALIDARE DATE SI ALGORITM PENTRU COD SI CHEIE
    !empty($data->username) &&
    !empty($data->parola) &&
    !empty($data->nume) &&
    !empty($data->prenume) &&
    !empty($data->email)
){
    $user->username = $data->username;
    $user->parola = $data->parola;
    $user->nume = $data->nume;
    $user->prenume = $data->prenume;
    $user->email = $data->email;
    $user->cod_resetare = "PLACEHOLDER CODE";
    $user->cheie_criptare = "PLACEHOLDER KEY";

    if($user->create()){
        http_response_code(201);
        echo json_encode(array("message" => "User successfully created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing or invalid data detected."));
}
?>
