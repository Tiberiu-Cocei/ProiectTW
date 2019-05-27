<?php
/*
  Cum se foloseste api-ul asta: metoda GET, link-ul http://localhost/api/user/get_by_name.php?username=aiciUsernameul
  Username-ul nu conteaza daca este cu litere mari sau nu
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(isset($_GET['username']))
  $user->username = $_GET['username'];
else die();

$user->getByName();

if($user->parola!=null){
    $user_arr = array(
        "username" => $user->username,
        "parola" => $user->parola,
        "nume" => $user->nume,
        "prenume" => $user->prenume,
        "email" => $user->email
    );

    http_response_code(200);
    echo json_encode($user_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Could not find any user with given username."));
}
?>
