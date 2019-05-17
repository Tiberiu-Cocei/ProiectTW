<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../public/imagesfavicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1><img src="../../Resources/logo.png" alt="logo" style="width:60%;height:60%;"></h1>
    <h2> Welcome to the Passer Password Manager. Using this web application, you can safely store passwords for any
        account that you have as well as additional information regarding it.
        Asdditionally, you can set reminders to change a password, generate safe passwords as well as export your data.</h2>

    <div class="text-center center">
        <div><input class="form-control dataField removeTopSpace" placeholder="username" id="username"
        ></div>
        <div><input type="password" class="form-control dataField" placeholder="password" id="password"
        ></div>
        <button onclick="location.href = '../index.php';" id="login" type="button"
                class="button middle innerButton"
                style="margin-top:25px;"><b>Login</b></button>
        <button onclick="location.href = 'accountCreation.php';" id="register" type="button"
                class="button middle innerButton"><b>Create account</b></button>
        <button onclick="location.href = 'forgotPassword.php';" id="reset" type="button"
                class="button middle innerButton">
            <b>Forgot password</b></button>
    </div>
</div>
</body>
</html>