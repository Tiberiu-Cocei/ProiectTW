<?php
//fisierul asta se ocupa cu crearea unui cont
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/account/create.php
  si corpul json:
  {
    "id_categorie" : "idCategorieDinObiect",
    "id_utilizator" : "idUtilizatorDinCookie",
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

if(!empty($data->id_categorie) && !empty($data->id_utilizator) && !empty($data->username) && !empty($data->parola)
                               && !empty($data->adresa_site) && !empty ($data->nume_site)){

    $account->id_categorie = $data->id_categorie;
    $account->id_utilizator = $data->id_utilizator;
    $account->username = $data->username;
    $account->parola = $data->parola;
    $account->adresa_site = $data->adresa_site;
    $account->nume_site = $data->nume_site;
    if(!empty($data->comentarii)) $account->comentarii = $data->comentarii;
      else $account->comentarii = "No comments";
    if(!empty($data->data_expirare)) $account->data_expirare = $data->data_expirare;
      else $account->data_expirare = date('Y-m-d', strtotime($Date. ' + 3 months'));
    $account->contor_utilizari = 0;
    $account->putere_parola = determineStrength($data->parola);

    if($account->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Account successfully created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create account."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing information."));
}
?>
