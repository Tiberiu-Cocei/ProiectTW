<?php
//fisierul asta returneaza parola decriptata a contului respectiv
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/TWPM/api/account/show_password.php?id_cont=PLACEHOLDER&id_utilizator=PLACEHOLDER
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/account.php';
include_once '../../includes/apiCall.php';

use Defuse\Crypto\Crypto; 
use Defuse\Crypto\Key; 
require "../../vendor/autoload.php";

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

if(isset($_GET['id_cont']) && isset($_GET['id_utilizator']))
  {
    $account->id_cont = $_GET['id_cont'];
    $account->id_utilizator = $_GET['id_utilizator']; 
  }
else die();

$ok = 0;
if($account->increment()){
    $parola = $account->getPassword(); //cripted
    if($parola !== NULL) {
      $ok = 1;

      //decriptare aici --
      $stringApi = 'http://localhost/TWPM/api/user/get_encryption_key.php?id_utilizator='. $account->id_utilizator; 

      $make_call = ApiCall('GET', $stringApi, json_encode($account->id_utilizator));

      $response  = json_decode($make_call, true);

      $stringKey = $response['message'];
      
      $key       = Key::loadFromAsciiSafeString( $stringKey );
      $decrypt   = Crypto::decrypt( $parola, $key);

      http_response_code(200);
      echo json_encode( $decrypt );
    }
}

if($ok == 0)
{
  http_response_code(404);
  echo json_encode("No data");
}
?>
