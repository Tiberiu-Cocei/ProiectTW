<?php
//fisierul asta este pentru testare; nu ar trebui sa fie posibila stergerea asta
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

$user->id_utilizator = $data->id_utilizator;
//TODO: daca nu exista user-ul
if($user->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "User was deleted."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete user."));
}
?>
