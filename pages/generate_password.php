<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate safe password</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1 class="text-center"><u><b>Generate password</b> </u></h1>
    <div class="text-center">
    </div>
    <div class="text-center center">
        <h2 class="form-control dataField password-generated" id="safePassword">
            Password goes here
        </h2>
        <!--
            <h2 style="text-align: center; margin-left:75px; margin-top: 125px;" class="form-control dataField" id="ASDASD"> Password goes here</h2>
        -->
        <button onclick="location.href = '#generatePassword';" id="generatePassword" type="button"
                style="margin-top:35px;"
                class="button middle innerButton"><b>Generate safe password</b></button>
        <button onclick="location.href = 'main_page.php';" id="return" type="button"
                class="button middle innerButton"><b>Return</b></button>
    </div>
</div>
</body>
</html>
