<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Account</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
    <script>
        function returnPage() {
            window.history.back();
        }
    </script>
</head>
<body>
<div class="outer middle inner">
<h1 class="text-center"><u><b>Add new account</b></u></h1>
    <div class="text-center center">
        <form action="./action_new_account.php" method="POST">
            <input type="text"    name="siteName" placeholder="site/application name"    class="form-control dataField" value="<?php if (isset($_COOKIE['siteName'])) echo $_COOKIE['siteName'];?>" >
            <input type="text"    name="username" placeholder="username"                 class="form-control dataField" value="<?php if (isset($_COOKIE['username'])) echo $_COOKIE['username'];?>" >
            <input type="password"name="password" placeholder="password"                 class="form-control dataField">
            <input type="password"name="passwordConfirm" placeholder="confirm password"  class="form-control dataField">
            <input type="text"    name="address"  placeholder="address"                  class="form-control dataField" value="<?php if (isset($_COOKIE['address']))  echo $_COOKIE['address'];?>" >
            <input type="text"    name="comments" placeholder="comments"                 class="form-control dataField" value="<?php if (isset($_COOKIE['comments'])) echo $_COOKIE['comments'];?>" >
            <input type="date"    name="reminderDate" placeholder="reminder date" class="form-control dataField"  value="<?php if (isset($_COOKIE['reminderDate'])) echo $_COOKIE['reminderDate'];?>" >
            <button type="submit" name="tryToCreateAccount"                              class="button middle innerButton">  <b>Create new account</b></button>
            <button type="submit" name="returnToMainPage"                                class="button middle innerButton">  <b>Return to main page</b></button>
            <h3>
                <?php if (isset($_COOKIE['response'])) 
                        echo $_COOKIE['response'];
                    else
                        echo ""; 
                ?></h3>
        </form>
    </div>
</div>
</body>
</html>