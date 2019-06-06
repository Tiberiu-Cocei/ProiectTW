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
    <li class="generatePassword"><a style="color:#f6cd61; font-weight: bold;" href="./generate_password.php">     Generate safe password                            </a></li>
    <li class="logout">          <a style="color:#f6cd61; font-weight: bold;" href="./account/logout.php">        Logout                                            </a></li>
  </ul>
</nav>

<div class="grid-container">
  <div class="center column" src="submain.php">
  <form action="main_page.php" method="post">
    <button onclick="location.href = 'new_category.php';" id="addCategory" type="button" class="buttonReversed middle innerButton"><b>Add new category</b></button>
    <button name="usageOrder"      type="submit" id="Usage"      class="buttonReversed middle innerButton"  style="margin-top:20px"><b>Accounts by usage</b></button>
    <button name="strengthOrder"   type="submit" id="Strength"   class="buttonReversed middle innerButton">                         <b>Accounts by password strength</b></button>
    <button name="showCategories"  type="submit"                 class="buttonReversed middle innerButton">                         <b>Categories</b></button>
  </form>

  <?php
//in acest bloc de php extragem toate denumirile conturilor pentru utiizatorul conectat (pentru afisare a butoanelor de categorii - cu functionalitate)
  $userApi = 'http://localhost/TWPM/api/user/get_by_name.php?username='.$_SESSION['username']; 

  $make_call = ApiCall('GET', $userApi, json_encode($_SESSION['username']));

  $response = json_decode($make_call, true);

  $data     = $response['id_utilizator'];

  if($data == "Could not find any user with given username." || $data == null)
  {
      header("Location: ./Login.php");
  }
  else
  {
    $_SESSION['id_utilizator'] = $data; 
    $categoriesApi = 'http://localhost/TWPM/api/category/get_by_user_id.php?id_utilizator='.$data; 

    $make_call = ApiCall('GET', $categoriesApi);

    $response = json_decode($make_call, true);

    $allCategories = array(); 

    echo "<form method=\"POST\">";
    foreach($response['records'] as $category) {
      echoCategoryButton( $category['nume_categorie'] );                            //adaugam butonul categoriei 
      $allCategories += [$category['nume_categorie'] => $category['id_categorie']]; //punem numele ( categoriei + id ) intr-o variabila
    }
  }
  ?>
</div>

<!-- de aici incepe coloana a doua !!!!!!!!! -->
<div class="center column" src="accounts.php">
  <?php 
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['usageOrder']))
    {
      $accounts = getAccounts('usage');
      unsetcookie("selectedCategoryID");
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['strengthOrder']))
    {
      $accounts = getAccounts('strength');
      unsetcookie("selectedCategoryID");
    }
    else
    {
      $accounts = getAccounts('justSelectedCategory', $allCategories); 
    }
  
  
  //verificam daca suntem in cadrul unei categorii si putem adauga butonul de adaugare a unui cont
  if(isset($_COOKIE['selectedCategoryID']))//(isACategorySelected($allCategories))
  {
  ?>
    <button onclick="location.href = 'new_account.php';" id="addSite" type="button" class="buttonReversed middle innerButton">
        <b>Add new account</b></button>
  <?php
  ;}

  if(isset($accounts) && isset($accounts['records']))
  {
    foreach($accounts['records'] as $account) { 
      echoAccount($account);
    }
  }

  //procesam si cererile de stergere pentru conturi:
  isAnAccountSelectedToDelete($accounts['records']); 

  //si cererile de stergere pentru categorii


  ?>
  
  </div> <!--cloana 2 - accounts e gata-->
</div> <!-- grid container -->

</body>
</html>
