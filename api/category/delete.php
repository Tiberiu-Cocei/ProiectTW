<?php
//fisierul asta va sterge o categorie; id-ul categoriei va fi dat de pagina respectiva
//TODO incomplet, va trebui realizata stergerea tuturor conturilor din categoria respectiva
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

$category->id_categorie = $data->id_categorie;

if($category->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "Category was deleted."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete category."));
}
?>
