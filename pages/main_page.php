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
    $buttonSettings = "<input type=\"submit\" name=\"$category_name\" value=\"$category_name\"
    class=\"button middle innerButton\"><br>"; 
    //onClick=\"document.location.href='./main_page.php'\"
    echo $buttonSettings;
  }

  function echoAccount($account)
  {
    $details = " <div class=\"textWrapper\">"; 
    $details = $details. "<h2>Username: ". $account['username'] . "</h2>";
    $details = $details. "<h2>Password: ". $account['parola'] . "</h2>"; 
    $details = $details. "<h2>Web adress: ". $account['adresa_site'] . "</h2>"; 
    $details = $details. "<h2>Web adress: ". $account['nume_site'] . "</h2>"; 
    $details = $details. "<h2>Comments: ". $account['comentarii'] . "</h2>"; 
    $details = $details. "<h2>Password safety: ". $account['putere_parola'] . "</h2>"; 
    $details = $details. "<h2>Reset reminder: None</h2>"; 
    $details = $details. "<h2>Add date: ". $account['data_adaugare'] . "</h2>"; 
    $details = $details. "<h2>Expire date: ". $account['data_expirare'] . "</h2>"; 

    $details = $details. "<button onclick=\"location.href = 'edit_account.php';\" 
                id=\"edit1\" type=\"button\"
                class=\"button \"><b>Edit account info</b></button>"; 
    $details = $details. "<button onclick=\"location.href = '#delete';\" id=\"delete1\" 
                type=\"button\"
                class=\"button buttonMargin\"><b>Delete entry</b></button>"; 
    $details = $details. "</div>"; 
    echo $details; 
  }

  function getAccounts($orderType, $allCategories = array())
  {
    if($orderType == 'strength' || $orderType == 'usage')
    {
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
          return getAccountsByCategory($value_category_id);
        }
      }
    }
    //if($orderType == 'none')
    $response['records'] = array();

    return $response;
  }

  function getAccountsByCategory($id_categorie)
  {
    $accountsApi = 'http://localhost/api/account/get_by_category.php?id_categorie='.$id_categorie; 

    $make_call = ApiCall('GET', $accountsApi, json_encode($id_categorie));

    return json_decode($make_call, true);
  }

  function isACategorySelected($allCategories)
  {
    foreach($allCategories as $key_category_name => $value_category_id) 
    {
      if(isset($_POST[$key_category_name]))
      {
        return true;
      }
    }
    return false; 
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
    <!-- <button name="usageOrder"      type="submit"                 class="buttonReversed middle innerButton">                         <b>Accounts by usage</b></button> -->
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
    $categoriesApi = 'http://localhost/api/category/get_by_user_id.php?id_utilizator='.$data; 

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
  //verificam daca suntem in cadrul unei categorii si putem adauga butonul de adaugare a unui cont
  if(isACategorySelected($allCategories))
  {
  ?>
  <button onclick="location.href = 'new_account.php';" id="addSite" type="button" class="buttonReversed middle innerButton"><b>Add new account</b></button>
  
  <?php
  ;}
  
  //in cazul in care s-a cerut, afisam conturile, filtrate corespunzator
  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['usageOrder']))
  {
    $_SESSION['canAddAccount'] = true; 
    $accounts = getAccounts('usage');  
  }
  else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['strengthOrder']))
  {
    $_SESSION['canAddAccount'] = true;
    $accounts = getAccounts('strength');
  }
  else 
  {
    $_SESSION['canAddAccount'] = true; 
    $accounts = getAccounts('justSelectedCategory', $allCategories); 
  }

  foreach($accounts['records'] as $account) { 
    echoAccount($account);
  }
  ?>
  
  </div> <!--accounts-->
</div> <!-- grid container -->

</body>
</html>
