<?php
//id_categorie va fi dat din pagina respectiva
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/api/category/update.php
  si corpul json:
  {
	 "id_categorie" : idCategorieDinObiectCRED,
	 "nume_categorie" : "numeNou"
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
if(!empty($data->nume_categorie) && !empty($data->id_categorie)){
  $category->nume_categorie = $data->nume_categorie;
  $category->id_categorie = $data->id_categorie;

  if($category->update()){
      http_response_code(204);
      echo json_encode(array("message" => "Successfully updated category name."));
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to update category name."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing category name or category id."));
}
?>
