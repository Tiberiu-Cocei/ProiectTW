<?php
    include_once '../../includes/apiCall.php';
    $data = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        if($password != $passwordConfirm) 
            $data = "Passwords do not match.";
        else{
            $hashedePwd = password_hash($password, PASSWORD_DEFAULT);

            $data_array =  array(
            "username" => $username,
            "parola" => $hashedePwd,
            "nume" => $fname,
            "prenume" => $lname,
            "email" => $email
          );

          $make_call = ApiCall('POST', 'http://localhost/TWPM/api/user/register.php', json_encode($data_array));

          $response = json_decode($make_call, true);
          $data     = $response['message'];
          if($data === "User successfully created.") {
              session_start();
              $_SESSION['username'] = $username;
              header("Location: ./Login.php");
          }
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account creation</title>
    <link rel="stylesheet" href="../../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1 class="text-center"><u><b>Account creation</b> </u></h1>
    <div class="text-center">
    </div>

    <form action="" method="POST">
        <div class=" text-center center">
            <div><input name="fname" class="form-control dataField" placeholder="first name" id="firstName" style="margin-top:50px;"
            ></div>
            <div><input name="lname"class="form-control dataField" placeholder="last name" id="lastName"
            ></div>
            <div><input name="email" class="form-control dataField" placeholder="e-mail" id="email"
            ></div>
            <div><input name="username"class="form-control dataField" placeholder="username" id="username"
            ></div>
            <div><input name="password" type="password" class="form-control dataField" placeholder="password" id="password"
            ></div>
            <div><input name="passwordConfirm" type="password" class="form-control dataField" placeholder="confirm password" id="confirmPassword"
            ></div>
            <h3> <?php if($data !==null) echo $data; ?> </h3>
            <button id="finishCreation" type="submit" style="margin-top:35px;" class="button middle innerButton">
                    <b>Create account</b></button>
        </div>
    </form>


</div>

</body>
</html>
