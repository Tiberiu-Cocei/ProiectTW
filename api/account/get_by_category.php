<?php
//fisierul asta returneaza toate conturile care apartin unei categorii date
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/account/get_by_category.php?id_categorie=aiciIdul
*/
//TODO: daca ar trebui sau nu sa returneze 404 daca nu exista conturi in categorie
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

if(isset($_GET['id_categorie']))
  $account->id_categorie = $_GET['id_categorie'];
else die();

if(!ctype_digit($account->id_categorie)) {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid category id detected."));
    die();
}

$stmt = $account->getByCategory();
$nr = $stmt->rowCount();

if($nr > 0){
    $accounts_arr=array();
    $accounts_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $account_item = array(
            "id_cont" => $id_cont,
            "username" => $username,
            "parola" => $parola,
            "adresa_site" => $adresa_site,
            "nume_site" => $nume_site,
            "comentarii" => $comentarii,
            "data_adaugare" => $data_adaugare,
            "data_expirare" => $data_expirare,
            "contor_utilizari" => $contor_utilizari,
            "putere_parola" => $putere_parola
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
