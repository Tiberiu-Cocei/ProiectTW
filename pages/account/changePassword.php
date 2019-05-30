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
    <div class="text-center">
    </div>
    <div class=" text-center center">
        <div><input type="password" class="form-control dataField" placeholder="current password" id="currentPassword"
                    style="margin-top:125px;"
        ></div>
        <div><input type="password" class="form-control dataField" placeholder="new password" id="newPassword"
        ></div>
        <div><input type="password" class="form-control dataField" placeholder="confirm new password"
                    id="confirmPassword"
        ></div>
        <button onclick="location.href = '../main_page.php';" id="resetPassword" type="button"
                style="margin-top:35px;"
                class="button middle innerButton"><b>Confirm new password</b></button>
        <button onclick="location.href = '../main_page.php';" id="return" type="button"
                class="button middle innerButton"><b>Cancel</b></button>
    </div>
</div>

</body>
</html>
