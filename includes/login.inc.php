<?php

    // //setcookie('beatrice','user',time() +3600,"/");
    // if(!isset($_POST['username']) || !isset($_POST['password']) )
    // {
    //     //header("Location:../pages/account/Login.php");
    // }
    // else
    // {

    //     $_SESSION['username'] = $_POST['username'];

    //     // $username = $_POST['username'];

    //     // echo "<p>".$_POST['username']."</p>";

    //     header("Location:../pages/index.php");
    // }

    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];

	// $mailuid = $_POST['mailuid'];
	// $password = $_POST['pwd'];

	if (empty($username) || empty($password)) {
		header("Location: ../index.php?error=emptyfields");
		exit();
	}
    else{
		header("Location: ../index.php");
		exit();
	}
?>
