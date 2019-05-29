<?php
//fisierul asta face un get all pentru user-ul autentificat; se obtine id-ul prin $_COOKIE(sau SESSION?)["id_utilizator"] din pagina web respectiva
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/category/get_by_user_id.php?id_utilizator=aiciIdulDinCookie
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

if(isset($_GET['id_utilizator']))
  $category->id_utilizator = $_GET['id_utilizator'];
else die();

$stmt = $category->getByUserId();
$nr = $stmt->rowCount();

if($nr > 0){
    $categories_arr=array();
    $categories_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $category_item=array(
            "id_categorie" => $id_categorie,
            "nume_categorie" => $nume_categorie
        );

        array_push($categories_arr["records"], $category_item);
    }

    http_response_code(200);
    echo json_encode($categories_arr);
} else {

   http_response_code(404);

   echo json_encode(
       array("message" => "No categories found.")
   );
}
