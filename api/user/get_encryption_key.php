<?php
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/TWPM/api/user/get_encryption_key.php/?id_utilizator=PLACEHOLDER
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id_utilizator = $_GET['id_utilizator'];

$user->getKeyByIdUtilizator(); 

if($user->cheie_criptare!=null){
    http_response_code(200);
    echo json_encode(array("message" => $user->cheie_criptare));
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Could not find key for a user with*" . $_GET['id_utilizator']. "*id."));
}
?>
