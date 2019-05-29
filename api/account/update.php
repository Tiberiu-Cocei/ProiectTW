<?php
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/account/update.php
  si corpul json:
  {
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

if($data->username !== null) $account->username = $data->username;
else $account->username = $accountAux->username;
if($data->parola !== null) { $account->parola = $data->parola; $account->putere_parola = determineStrength($data->parola); }
else { $account->parola = $accountAux->parola; $account->putere_parola = $accountAux->putere_parola; }
if($data->adresa_site !== null) $account->adresa_site = $data->adresa_site;
else $account->adresa_site = $accountAux->adresa_site;
if($data->nume_site !== null) $account->nume_site = $data->nume_site;
else $account->nume_site = $accountAux->nume_site;
if($data->comentarii !== null) $account->comentarii = $data->comentarii;
else $account->comentarii = $accountAux->comentarii;
if($data->data_expirare !== null) $account->data_expirare = $data->data_expirare;
else $account->data_expirare = $accountAux->data_expirare;

if($account->update()){
    http_response_code(204);
    echo json_encode(array("message" => "Successfully updated account info."));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update account info."));
  }
?>
