<!DOCTYPE html>
<?php
  include_once '../includes/apiCall.php';
  if(!isset($_SESSION))
  {
      session_start();
  }
  if($_SESSION['username'] === null) header("Location:./Login.php");


  function echoCategoryButton($category_name)
  {
    $buttonSettings = "<input type=\"submit\" name=\"$category_name\" value=\"$category_name\" class=\"button middle innerButton\"
    onClick=\"document.location.href='./main_page.php'\"><br>";
    echo $buttonSettings;
  }

  function echoAccount($account)
  {
    $details = "<div class=\"textWrapper\">"; 
    $details = $details. "<h2>Username: ". $account['username'] . "</h2>";
    $details = $details. "<h2>Password: ". $account['parola'] . "</h2>"; 
    $details = $details. "<h2>Web adress: ". $account['adresa_site'] . "</h2>"; 
    $details = $details. "<h2>Web adress: ". $account['nume_site'] . "</h2>"; 
    $details = $details. "<h2>Comments: ". $account['comentarii'] . "</h2>"; 
    $details = $details. "<h2>Password safety: ". $account['putere_parola'] . "</h2>"; 
    $details = $details. "<h2>Reset reminder: None</h2>"; 
    $details = $details. "<h2>Add date: ". $account['data_adaugare'] . "</h2>"; 
    $details = $details. "<h2>Expire date: ". $account['data_expirare'] . "</h2>"; 

    $details = $details. "<form method=\"POST\"><input type=\"submit\" name=\"Showpassword".$account['id_cont']."\" value=\"Show password\" 
                style=\"font-weight: bold;\" class=\"button\">" ;

    $details = $details. "<form method=\"POST\" action=\"#\"><input type=\"submit\" name=\"editAccountInfo".$account['id_cont']."\" value=\"Edit account info\" 
                style=\"font-weight: bold;\" class=\"button\">" ;

    $details = $details. "<input type=\"submit\" name=\"deleteId".$account['id_cont']."\" value=\"Delete entry\" 
    style=\"font-weight: bold;\" class=\"button\">" ;

     
    $details = $details. "</div>"; 
    echo $details; 
  }

  function getAccounts($orderType, $allCategories = array())
  {
    if($orderType == 'strength' || $orderType == 'usage')
    {
      //TODO: de ce aici da connection failure?!!?  
      $accountsApi = 'http://localhost/TWPM/api/account/get_by_'.$orderType.'.php?id_utilizator='.$_SESSION['id_utilizator']."'"; 
  
      $make_call = ApiCall('GET', $accountsApi, json_encode($_SESSION['id_utilizator']));

      return json_decode($make_call, true);
    }
    else if($orderType = 'justSelectedCategory')
    {
      foreach($allCategories as $key_category_name => $value_category_id) 
      {
        if(isset($_POST[$key_category_name]))
        {
          setcookie("selectedCategoryID", $value_category_id, time() + 3600, "/");
          return getAccountsByCategory($value_category_id);
        }
      }
    }
    //if($orderType == 'none')
    setcookie("selectedCategoryID", null, -1, "/");

    $response['records'] = array();

    return $response;
  }

  function getAccountsByCategory($id_categorie)
  {
    $accountsApi = 'http://localhost/api/account/get_by_category.php?id_categorie='.$id_categorie; 

    $make_call = ApiCall('GET', $accountsApi, json_encode($id_categorie));

    return json_decode($make_call, true);
  }

  // function isACategorySelected($allCategories) //sub forma de vector asociativ
  // {
  //   foreach($allCategories as $key_category_name => $value_category_id) 
  //   {
  //     if(isset($_POST[$key_category_name]))
  //     {
  //       setcookie("selectedCategoryID", $value_category_id, time() + 3600, "/");
  //       return true;
  //     }
  //   }
  //   return false; 
  // }

  function isAnAccountSelectedToDelete($allAccounts)
  {
    foreach($allAccounts as $account)
    {
      //echo $account['id_cont']."<BR>"; 
      $buttonName = "deleteId". $account['id_cont'];
      echo $buttonName; 
      if(isset($_POST[$buttonName]))
      {
        $accountApi = 'http://localhost/api/account/delete.php'; 
  
        $make_call = ApiCall('POST', $accountApi, json_encode(array("id_cont"=>$account['id_cont'])));

        //echo  $make_call;
      }
    }
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
      setcookie("selectedCategoryID", null, -1, '/');
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['strengthOrder']))
    {
      $accounts = getAccounts('strength');
      setcookie("selectedCategoryID", null, -1, '/');
    }
    else
    {
      $accounts = getAccounts('justSelectedCategory', $allCategories); 
    }
  
  
  //verificam daca suntem in cadrul unei categorii si putem adauga butonul de adaugare a unui cont
  if(isset($_COOKIE['selectedCategoryID']))//(isACategorySelected($allCategories))
  {
  ?>
    <button onclick="location.href = 'new_account.php';" id="addSite" type="button" class="buttonReversed middle innerButton"><b>Add new account</b></button>
    
  <?php
  ;}

  foreach($accounts['records'] as $account) { 
    echoAccount($account);
  }

  //procesam si cererile de stergere pentru conturi:
  isAnAccountSelectedToDelete($accounts['records']); 

  //si cererile de stergere pentru categorii


  ?>
  
  </div> <!--cloana 2 - accounts e gata-->
</div> <!-- grid container -->

</body>
</html>
