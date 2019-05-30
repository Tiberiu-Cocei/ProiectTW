<?php
//fisierul asta returneaza toate conturile in format json pentru a fi puse si descarcate intr-un fisier
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/export/to_json.php?id_utilizator=aiciIdul
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

if(isset($_GET['id_utilizator']))
  $account->id_utilizator = $_GET['id_utilizator'];
else die();

$stmt = $account->getForExport();
$nr = $stmt->rowCount();

if($nr > 0){
    $accounts_arr=array();
    $accounts_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $account_item = array(
            "nume_categorie" => $nume_categorie,
            "nume_site" => $nume_site,
            "adresa_site" => $adresa_site,
            "username" => $username,
            "parola" => $parola,
            "comentarii" => $comentarii,
            "data_adaugare" => $data_adaugare,
            "data_expirare" => $data_expirare
        );

    array_push($accounts_arr["records"], $account_item);
}

    http_response_code(200);
    echo json_encode($accounts_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No accounts found."));
}
?>
