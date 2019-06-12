<?php
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/account/update.php
  si corpul json:
  {
    "id_utilizator" : "id_utilizatorCeAreAcestCont",
    "id_cont" : "idContDinPaginaWeb",
    "username" : "usernamePtSite",
    "parola" : "parolaPtSite",
    "adresa_site" : "adresaSite",
    "nume_site" : "numeSite",
    "comentarii" : "comentariiPtSiteNullable",
    "data_expirare" : "dataCandExpiraParolaNullable"
  }
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/account.php';
include_once '../algorithms/password_strength.php';
include_once '../../includes/apiCall.php';

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
require "../../vendor/autoload.php";

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);
$data = json_decode(file_get_contents("php://input"));

$accountAux = new Account($db);
$account->id_cont = $data->id_cont;
$accountAux->id_cont = $data->id_cont;
$accountAux->getById();

if($accountAux->putere_parola === null) {
  http_response_code(404);
  echo json_encode(array("message" => "Account does not exist"));
  die();
}

if($data->username !== null)$account->username = $data->username;
else $account->username = $accountAux->username;
if($data->adresa_site !== null) $account->adresa_site = $data->adresa_site;
else $account->adresa_site = $accountAux->adresa_site;
if($data->nume_site !== null) $account->nume_site = $data->nume_site;
else $account->nume_site = $accountAux->nume_site;
if($data->comentarii !== null) $account->comentarii = $data->comentarii;
else $account->comentarii = $accountAux->comentarii;
if($data->data_expirare !== null) $account->data_expirare = $data->data_expirare;
else $account->data_expirare = $accountAux->data_expirare;

if($data->parola !== null) 
{ 
  $account->putere_parola = determineStrength($data->parola); 
  $account->parola = $data->parola;
  $id_utilizator   = $data->$id_utilizator; 

  $stringApi = 'http://localhost/TWPM/api/user/get_encryption_key.php?id_utilizator='.$id_utilizator;

  $make_call = ApiCall('GET', $stringApi, json_encode($id_utilizator));

  $response  = json_decode($make_call, true);

  $stringKey = $response['message'];
   //"def00000e243a7a6a469a7b29f97882c451d92cda12adf5865a9bf441a784c0b65945cc72d99ed63f722496d639ca69a2141a2c0195419404c8fe94a1b1efd76614ef4d1"; 

  $key       = Key::loadFromAsciiSafeString( $stringKey );
  $encrypt   = Crypto::encrypt( "asdh".$data->$id_utilizator, $key); //account->parola

  $account->parola = $encrypt; 
}
else 
{ $account->parola = $accountAux->parola; 
  $account->putere_parola = $accountAux->putere_parola; 
}


if($account->update()){
    http_response_code(204);
    echo json_encode(array("message" => "Successfully updated account info."));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update account info."));
  }
?>
