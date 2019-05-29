<?php
//fisierul asta returneaza numarul de conturi care au parola expirata
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/account/detect_expired.php?id_utilizator=aiciIdUtilizator
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");

include_once '../config/database.php';
include_once '../objects/account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

if(isset($_GET['id_utilizator']))
  $account->id_utilizator = $_GET['id_utilizator'];
else die();

$account->data_expirare = date('Y-m-d');

$nr = $account->getExpiredCount();

if($nr !== null){
    http_response_code(200);
    if($nr > 0)
      echo json_encode(array("message" => "There are " . $nr . " account(s) that have expired passwords." ));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to detect expired passwords."));
}
?>
