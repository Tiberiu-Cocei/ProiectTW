<?php
//fisierul asta returneaza parola decriptata a contului respectiv -> adica utilizatorul utilizeaza parola
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/TWPM/api/account/password_decrypt.php?password=PLACEHOLDER&id_utilizator=PLACEHOLDER&id_cont=PLACEHOLDER
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

if(isset($_GET['password']) && isset($_GET['id_utilizator']) && isset($_GET['id_cont']))
{
  $account->id_cont       = $_GET['id_cont'];
  $account->password      = $_GET['password'];
  $account->id_utilizator = $_GET['id_utilizator']; 
}
else die();

$ok = 0;
if($account->increment(1)){
    $ok = 1; 

    $stringApi = 'http://localhost/TWPM/api/user/get_encryption_key.php?id_utilizator='. $account->id_utilizator; 

    $make_call = ApiCall('GET', $stringApi, json_encode($account->id_utilizator));

    $response  = json_decode($make_call, true);

    $stringKey = $response['message'];
    
    $key       = Key::loadFromAsciiSafeString( $stringKey );
    $decrypt   = Crypto::decrypt( $account->password, $key);

    http_response_code(200);
    echo json_encode( $decrypt );
}

if($ok == 0)
{
  http_response_code(404);
  echo json_encode("No data");
}
?>
