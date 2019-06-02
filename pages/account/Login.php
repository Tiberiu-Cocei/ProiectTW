<?php
    include_once '../../includes/apiCall.php';
    $data = null;
    if($_SERVER["REQUEST_METHOD"] == "POST") {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $data_array =  array(
        "username" => $username,
        "parola" => $password
      ); 

      $make_call = ApiCall('POST', 'http://localhost/TWPM/api/user/loginPentruParolaCriptata.php', json_encode($data_array));

      $response = json_decode($make_call, true);
      $data     = $response['message'];
      if($data === "Successfully logged in." || $data === null) {
        session_start();
        $_SESSION['username'] = $username;
        
        //intr-un cookie vom avea id-ul + username-ul userului (pentru celelalte pagini)
        $make_call = ApiCall('GET', 'http://localhost/TWPM/api/user/get_by_name.php?username='.$username);
        $response = json_decode($make_call, true);
        setcookie("userID",   $response['id_utilizator'], time() + 3600, "/");
        setcookie("usernameConnected", $username, time() + 3600, "/");

        header("Location: ../main_page.php");
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1><img src="../../public/images/logo.png" alt="logo" style="width:60%;height:60%;"></h1>
    <h2> Welcome to the Passer Password Manager. Using this web application, you can safely store passwords for any
        account that you have as well as additional information regarding it.
        Additionally, you can set reminders to change a password, generate safe passwords as well as export your data.</h2>

    <div class="text-center center form-control removeTopSpace">

    <form action="" method="post">
        <input type="text" name="username" placeholder="username" class="form-control dataField removeTopSpace">
        <input type="password" name="password" placeholder="password" class="form-control dataField">
        <h3> <?php if($data !==null) echo $data; ?> </h3>
        <button type="submit" name="login" class="button middle innerButton" style="margin-top:25px;" ><b> Login </b></button>
    </form>

    <button onclick="location.href = 'accountCreation.php';" id="register" type="button"
            class="button middle innerButton"><b>Sign in</b></button>
    </div>
</div>
</body>
</html>
