<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit account</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
</head>
<body>

<?php 
    if(isset($_GET['id_account_to_be_edited']))
    {
        $_SESSION['id_account_to_be_edited'] = $_GET['id_account_to_be_edited']; 
        ?>

        <div class="outer middle inner">
        <h1 class="text-center"><u><b>Edit your account informations:</b></u></h1>
            <div class="text-center center">
                <form action="./edit_account_action.php" method="POST">
                    <h3> If you leave a field empty, its value is not going to change. </h3>
                    <input type="text"    name="siteNameEdited" placeholder="site/application name" class="form-control dataField"  value="<?php if (isset($_COOKIE['siteNameEdit']))     echo $_COOKIE['siteNameEdited'];?>" >
                    <input type="text"    name="usernameEdited" placeholder="username"              class="form-control dataField"  value="<?php if (isset($_COOKIE['usernameEdit']))     echo $_COOKIE['usernameEdited'];?>" >
                    <input type="password"name="passwordEdited" placeholder="password"              class="form-control dataField">
                    <input type="text"    name="addressEdited"  placeholder="Web adress for site"   class="form-control dataField"  value="<?php if (isset($_COOKIE['addressEdit']))      echo $_COOKIE['addressEdited'];?>" >
                    <input type="text"    name="commentsEdited" placeholder="comments"              class="form-control dataField"  value="<?php if (isset($_COOKIE['commentsEdit']))     echo $_COOKIE['commentsEdited'];?>" >
                    <input type="date"    name="reminderDateEdited" placeholder="reminder date"     class="form-control dataField"  value="<?php if (isset($_COOKIE['reminderDateEdit'])) echo $_COOKIE['reminderDateEdited'];?>" >
                    <button type="submit" name="tryToEdit"          class="button middle innerButton">  <b>Edit this informations</b></button>
                    <button type="submit" name="returnToMainPage"   class="button middle innerButton">  <b>Return to main page</b></button>
                    <h3>
                        <?php if (isset($_COOKIE['response'])) 
                                {
                                    echo $_COOKIE['response'];
                                    setcookie("response", null, -1, "/");
                                }
                            else
                                echo ""; 
                        ?>
                    </h3>
                </form>
            </div>
        </div>
    <?php 
    } ?>

</body>
</html>