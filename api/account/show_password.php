<?php
//fisierul asta returneaza parola decriptata a contului respectiv
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/account/show_password.php?id_cont=aiciIdul
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

if(isset($_GET['id_cont']))
  $account->id_cont = $_GET['id_cont'];
else die();

$ok = 0;
if($account->increment()){
    $parola = $account->getPassword();
    if($parola !== NULL) {
      $ok = 1;
      http_response_code(200);
      echo json_encode($parola);
    }
}

if($ok == 0) http_response_code(404);
?>
