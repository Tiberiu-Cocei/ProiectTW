<?php
//fisierul asta creaza o noua categorie; se obtine id-ul prin $_COOKIE(sau SESSION?)["id_utilizator"] din pagina web respectiva
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/category/create.php
  si corpul json:
  {
	 "id_utilizator" : idUtilizatorDinCookie,
	 "nume_categorie" : "numeCategorie"
  }
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if($data->nume_categorie !== null){
    $category->id_utilizator = $data->id_utilizator;
    $category->nume_categorie = $data->nume_categorie;

    if($category->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Category successfully created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create category."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing category name."));
}
?>
