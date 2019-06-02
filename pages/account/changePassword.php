<?php 
    session_start(); 
    include_once '../../includes/unsetCookies.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
    <link rel="stylesheet" href="../../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1 class="text-center"><u><b>Change password</b> </u></h1>
    <div class=" text-center center">
        <form action="./action_change_password.php" method="POST">
            <input  type="password"   name="oldPassword"         class="form-control dataField"    placeholder="old password"         >
            <input  type="password"   name="newPassword"         class="form-control dataField"    placeholder="new password"         >
            <input  type="password"   name="newPasswordConfirm"  class="form-control dataField"    placeholder="confirm new password" >
            <button type="submit" name="tryToChangePassword" class="button middle innerButton"><b>Change it</b></button>
            <button type="submit" name="returnToMainPage"    class="button middle innerButton"><b>Back to main page</b></button>
            <h3>
                <?php if (isset($_COOKIE['response'])) 
                        {
                            echo $_COOKIE['response'];
                            if($_COOKIE['response'] == "return")
                                header("Location: ../main_page.php");
                            unsetCookie("response"); 
                        }
                     
                ?></h3>
        </form>
    </div>
</div>

</body>
</html>
