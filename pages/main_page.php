<!DOCTYPE html>
<?php
  include_once '../includes/apiCall.php';
  include_once './main_page_functions.php';
  if(!isset($_SESSION))
  {
      session_start();
  }
  if($_SESSION['username'] === null)
  {
    header("Location:./Login.php");
  }
?>

<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>Main page</title>
  <link rel="stylesheet" href="../public/css/main_page.css">
  <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>
<body>

<nav><BR>
  <ul class="menuBar">
    <li class="myAccount">       <a style="color:#f6cd61; font-weight: bold;">                                    Signed in as: <?php echo $_SESSION['username']; ?></a></li>
    <li class="changePassword">  <a style="color:#f6cd61; font-weight: bold;" href="./account/changePassword.php">Change password                                   </a></li>
    <li class="exportData">      <a style="color:#f6cd61; font-weight: bold;" href="export.php">                  Export data                                       </a></li>
    <li class="importData">      <a style="color:#f6cd61; font-weight: bold;" href="import.php">                  Import data                                       </a></li>
    <li class="generatePassword"><a style="color:#f6cd61; font-weight: bold; margin-left:100px;" href="./generate_password.php">     Generate safe password                            </a></li>
    <li class="logout">          <a style="color:#f6cd61; font-weight: bold;" href="./account/logout.php">        Logout                                            </a></li>
  </ul>
</nav>



<div class="grid-container">
  <div class="center column" src="submain.php">
  <form action="./main_page.php" method="post">
    <button onclick="location.href = 'new_category.php';" id="addCategory" type="button" class="buttonReversed middle innerButton"><b>Add new category</b></button>
    <button name="usageOrder"      type="submit" id="Usage"      class="buttonReversed middle innerButton"  style="margin-top:20px"><b>Accounts by usage</b></button>
    <button name="strengthOrder"   type="submit" id="Strength"   class="buttonReversed middle innerButton">                         <b>Accounts by password strength</b></button>
    <button name="showCategories"  type="submit"                 class="buttonReversed middle innerButton">                         <b>Categories</b></button>
  </form>

      <?php
      showCategoriesColumn(); //afiseaza toate categoriile acelui user in prima coloana
      ?>
    </div>

    <div>
      <?php
        showAccountColumn(); //afiseaza conturile corespunzatoare categoriei selectate in a doua coloana
      ?> 
    </div> <!--cloana 2 - accounts e gata-->

  </div> <!-- grid container -->

</body>
</html>
