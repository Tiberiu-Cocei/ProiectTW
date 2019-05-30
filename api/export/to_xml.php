<?php
//fisierul asta returneaza toate conturile in format xml pentru a fi puse si descarcate intr-un fisier
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/export/to_xml.php?id_utilizator=aiciIdul
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
    $return_string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $return_string =  $return_string . "<account>\n" . "  <category_name>" . $nume_categorie . "</category_name>\n"
                        . "  <website_name>" . $nume_site . "</website_name>\n" . "  <website_address>" . $adresa_site . "</website_address>\n"
                        . "  <username>" . $username . "</username>\n" . "  <password>" .$parola . "</password>\n"
                        . "  <comments>" . $comentarii . "</comments>\n" . "  <add_date>" .$data_adaugare . "</add_date>\n"
                        . "  <expire_date>" . $data_expirare . "</expire_date>\n" . "</account>\n";
}
    http_response_code(200);
    echo $return_string;
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No accounts found."));
}
?>
