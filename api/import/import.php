<?php
//fisierul asta va importa conturile din fisierul uploadat
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/TWPM/api/import/import.php
  si corpul json:
  {
   "id_utilizator" : idUtilizatorDinSession,
   "nume_fisier" : numeleFisierului //va avea extensia .json - EXEMPLU: file.json
  }
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/account.php';
include_once '../objects/category.php';
include_once '../algorithms/password_strength.php';

$data = json_decode(file_get_contents("php://input"));

if(empty($data->id_utilizator) || empty($data->nume_fisier)){
  http_response_code(400);
  echo json_encode(array("message" => "Missing user id or file name."));
  die();
}

$fileName = $data->nume_fisier;

if(substr($fileName, -4) != '.xml' && substr($fileName, -4) != '.csv' && substr($fileName, -5) != '.json'){
  http_response_code(400);
  echo json_encode(array("message" => "Invalid file type. Importing only works with .csv, .xml and .json formats."));
}

$fileData = file_get_contents('../../pages/'.$fileName);

//XML to JSON
if(substr($fileName, -4) == '.xml') {
  $xml = simplexml_load_string($fileData);
  $fileData = json_encode($xml);
}
//CSV to JSON
else if(substr($fileName, -4) == '.csv') {
  $csv = array_map("str_getcsv", explode("\n", $fileData)); //str_getcsv parses a CSV string into an array
  $fileData = json_encode($csv);
}

$nrCreated = 0;
$someArray = json_decode($fileData, true);
var_dump($someArray);
$error = json_last_error();
if($error == 0) {
  $database = new Database();
  $db = $database->getConnection();
  $arraySize = count($someArray);
  for($i = 0; $i < $arraySize; $i++)
  {
    $accountAux = new Account($db);
    $accountAux->id_utilizator = $data->id_utilizator;
    $categoryAux = new Category($db);
    $categoryAux->id_utilizator = $data->id_utilizator;

    //ADAUGA CRIPTAREA AICI ------------------------------------------------------------------------
    if(isset($someArray[$i]['parola'])) $accountAux->parola = $someArray[$i]['parola'];
    else if(isset($someArray[$i]['password'])) $accountAux->parola = $someArray[$i]['password'];
    else continue;
    //----------------------------------------------------------------------------------------------

    if(isset($someArray[$i]['nume_categorie'])) $categoryAux->nume_categorie = $someArray[$i]['nume_categorie'];
    else if(isset($someArray[$i]['category_name'])) $categoryAux->nume_categorie = $someArray[$i]['category_name'];
    else if(isset($someArray[$i]['category'])) $categoryAux->nume_categorie = $someArray[$i]['category'];
    else $categoryAux->nume_categorie = 'Imported';

    $id_categorie = $categoryAux->getByCategoryName();
    $accountAux->id_categorie = $id_categorie;

    if(isset($someArray[$i]['username'])) $accountAux->username = $someArray[$i]['username'];
    else if(isset($someArray[$i]['nume_utilizator'])) $accountAux->username = $someArray[$i]['nume_utilizator'];
    else $accountAux->username = 'none';

    if(isset($someArray[$i]['adresa_site'])) $accountAux->adresa_site = $someArray[$i]['adresa_site'];
    else if(isset($someArray[$i]['web_address'])) $accountAux->adresa_site = $someArray[$i]['web_address'];
    else if(isset($someArray[$i]['website'])) $accountAux->adresa_site = $someArray[$i]['website'];
    else if(isset($someArray[$i]['address'])) $accountAux->adresa_site = $someArray[$i]['address'];
    else $accountAux->adresa_site = 'none';

    if(isset($someArray[$i]['nume_site'])) $accountAux->nume_site = $someArray[$i]['nume_site'];
    else if(isset($someArray[$i]['site_name'])) $accountAux->nume_site = $someArray[$i]['site_name'];
    else if(isset($someArray[$i]['name'])) $accountAux->nume_site = $someArray[$i]['name'];
    else if(isset($someArray[$i]['title'])) $accountAux->nume_site = $someArray[$i]['title'];
    else $accountAux->nume_site = 'none';

    if(isset($someArray[$i]['comentarii'])) $accountAux->comentarii = $someArray[$i]['comentarii'];
    else if(isset($someArray[$i]['comments'])) $accountAux->comentarii = $someArray[$i]['comments'];
    else if(isset($someArray[$i]['description'])) $accountAux->comentarii = $someArray[$i]['description'];
    else if(isset($someArray[$i]['descriere'])) $accountAux->comentarii = $someArray[$i]['descriere'];
    else $accountAux->comentarii = 'none';

    $accountAux->data_expirare = date('Y-m-d', strtotime(' + 3 months'));
    $accountAux->contor_utilizari = 0;
    $accountAux->putere_parola = determineStrength($accountAux->parola);

    if($accountAux->create()) $nrCreated++;
  }
}

if($nrCreated > 0){
    http_response_code(201);
    echo json_encode(array("message" => "Successfully imported ".$nrCreated." accounts."));
}
else {
    http_response_code(400);
    echo json_encode(array("message" => "Failed to import."));
}
?>
