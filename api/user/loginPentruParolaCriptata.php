<?php
/*
  Cum se foloseste api-ul asta: metoda POST, link-ul http://localhost/TWPM/api/user/loginPentruParolaCriptata.php
  si corpul json:
  {
	  "username" : "username",
	  "parola" : "parola" //parola in plaintext
  }
  Username-ul nu conteaza daca este cu litere mari sau nu, la login face lowercase de el
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(empty($data->username) && empty($data->parola)) {
    http_response_code(400);
    echo json_encode(array("message" => "Login failed. Missing username or password."));
}
else{
    $user->username = strtolower($data->username);
    $user->parola = $data->parola;
    $user->loginPentruParolaCriptata();

    if($user->parola !== null){
        $pwdCheck = password_verify($data->parola, $user->parola);
        if($pwdCheck == false){
            http_response_code(401);
            echo json_encode(array("message" => "Login failed. Incorrect password."));
        }
        else{
            http_response_code(200);
            echo json_encode(array("message" => "Successfully logged in."));
        }
    }
    else {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed. Incorrect username-password combination."));
    }
}

?>
