<?php
//fisierul asta va sterge un cont; id-ul contului va fi dat de pagina respectiva
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/account/delete.php
  si corpul json:
  {
   "id_cont" : idContDinObiectCRED
  }
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

$data = json_decode(file_get_contents("php://input"));

$account->id_cont = $data->id_cont;

if($account->delete()){
    http_response_code(204);
    echo json_encode(array("message" => "Account was deleted."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete account."));
}
?>
