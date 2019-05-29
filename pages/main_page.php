<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <title>Main page</title>
    <link rel="stylesheet" href="../public/css/main_page.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>

<body>
<nav>
    <ul class="menuBar">
        <li class="myAccount"><a>Signed in as: <?php echo $_SESSION['username']; ?></a></li>
        <li class="changePassword"><a href="./account/changePassword.php">Change password</a>
        </li>
        <li class="exportData"><a href="export.php">Export data</a></li>
        <li class="generatePassword"><a href="./generate_password.php">Generate safe password</a></li>
        <li class="logout"><a href="./account/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="grid-container">
    <iframe class="ierarhie center grid-item" src="submain.php"></iframe>
    <iframe class="categorie center grid-item" src="category.php"></iframe>
    <iframe class="conturi_site center grid-item" src="accounts.php"></iframe>
</div>

</body>
</html>