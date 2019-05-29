<?php
    // if ($_SERVER['REQUEST_METHOD'] === 'POST')
    // {
    //     $fname = $_POST['fname'];
    //     $lname = $_POST['lname'];
    //     $email = $_POST['email'];
    //     $username = $_POST['username'];
    //     $password = $_POST['password'];
    //     $passwordConfirm = $_POST['passwordConfirm'];

    //     require_once("../../api/config/database.php"); 

    //     $query = "INSERT into users (  email, password, username)
    //                 VALUES ('$email', '".md5($password)."', '$username')";
    //     $result = mysqli_query($connection,$query);

    //     if($result)
    //     {
    //         session_start();
    //         $_SESSION['email'] = $email;
    //         $_SESSION['name'] = $fname." ".$lname;

    //         header("Location:../index.php");

    //     }
    // }
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
            <button id="finishCreation" type="submit" style="margin-top:35px;" class="button middle innerButton">
                    <b>Create account</b></button>
        </div>
    </form>


</div>

</body>
</html>
