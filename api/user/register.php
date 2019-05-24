<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../algorithms/reset_key.php';
include_once '../algorithms/encryption_key.php';
include_once '../algorithms/user_validation.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$mesaj = isValid($data);

$userAux = new User($db);
$userAux->username = $data->username;
$userAux->getByName();
if($userAux->parola != null) {
    http_response_code(400);
    echo json_encode(array("message" => "Username already in use."));
    die();
}

if($mesaj === "Valid"){
    $user->username = $data->username;
    $user->parola = $data->parola;
    $user->nume = $data->nume;
    $user->prenume = $data->prenume;
    $user->email = $data->email;
    $user->cod_resetare = generateResetKey();
    $user->cheie_criptare = generateEncryptionKey($data->username);

    if($user->create()){
        http_response_code(201);
        echo json_encode(array("message" => "User successfully created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => $mesaj));
}
?>
