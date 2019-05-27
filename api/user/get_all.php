<?php
//fisierul asta face un get all, este pentru testare
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->read();
$nr = $stmt->rowCount();

if($nr > 0){
    $users_arr=array();
    $users_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $user_item=array(
            "id_utilizator" => $id_utilizator,
            "username" => $username,
            "parola" => $parola,
            "nume" => $nume,
            "prenume" => $prenume,
            "email" => $email,
            "cod_resetare" => $cod_resetare,
            "cheie_criptare" => $cheie_criptare
        );

        array_push($users_arr["records"], $user_item);
    }

    http_response_code(200);
    echo json_encode($users_arr);
} else {

   http_response_code(404);

   echo json_encode(
       array("message" => "No users found.")
   );
}
