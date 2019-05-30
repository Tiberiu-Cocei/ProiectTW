<?php
//fisierul asta returneaza toate conturile in format csv pentru a fi puse si descarcate intr-un fisier
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/export/to_csv.php?id_utilizator=aiciIdul
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
    $return_string = "Category name,Site name,Site address,Username,Password,Comments,Add date,Expire date\n";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $return_string = $return_string . $nume_categorie . "," . $nume_site . "," . $adresa_site . "," . $username . "," .
                         $parola . "," . $comentarii . "," . $data_adaugare . "," . $data_expirare . "\n";
}
    http_response_code(200);
    echo $return_string;
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No accounts found."));
}
?>
