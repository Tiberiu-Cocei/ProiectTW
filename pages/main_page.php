<!DOCTYPE html>
<?php
  if(!isset($_SESSION))
  {
      session_start();
  }
  if($_SESSION['username'] === null) header("Location:./Login.php");

  function echoCategoryButton($category_name)
  {
    // $buttonSettings = "<button onclick=\" \$_SESSION['current_category'] = \"CURRENT\" ; location.href = '#"
    //                   .$category_name
    //                   ."';\""
    //                   ."id=\""
    //                   .$category_name
    //                   ."\" "
    //                   ."type=\"button\" class=\"button middle innerButton\">"
    //                   ."<b>".$category_name."</b></button>"; 
    $_SESSION['current_category'] = "CATEGORIA HARD"; 

    $buttonSettings = "<input type=\"button\" value=\"$category_name\" 
    class=\"button middle innerButton\"
    onClick=\"document.location.href='./main_page.php'\" </input><br>"; 

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

  function getAccounts($orderType)
  {
    if($orderType == 'strength' || $orderType == 'usage')
    {
      $accountsApi = 'http://localhost/TWPM/api/account/get_by_'.$orderType.'.php?id_utilizator='.$_SESSION['id_utilizator']."'"; 
  
      $make_call = ApiCall('GET', $accountsApi, json_encode($_SESSION['id_utilizator']));

      $response = json_decode($make_call, true);
    }
    else 
    {
      $response['records'] = array();
    } 
    return $response;
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
<nav>
    <ul class="menuBar">
        <li class="myAccount">       <a style="color:#f6cd61;">                                    Signed in as: <?php echo $_SESSION['username']; ?></a></li>
        <li class="changePassword">  <a style="color:#f6cd61;" href="./account/changePassword.php">Change password                                   </a></li>
        <li class="exportData">      <a style="color:#f6cd61;" href="export.php">                  Export data                                       </a></li>
        <li class="generatePassword"><a style="color:#f6cd61;" href="./generate_password.php">     Generate safe password                            </a></li>
        <li class="logout">          <a style="color:#f6cd61;" href="./account/logout.php">        Logout                                            </a></li>
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
      include_once '../includes/apiCall.php';
      $_SESSION['current_category'] = "";

      $userApi = 'http://localhost/TWPM/api/user/get_by_name.php?username='.$_SESSION['username']; 

      $make_call = ApiCall('GET', $userApi, json_encode($_SESSION['username']));

      //echo $make_call;

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

        foreach($response['records'] as $category) {
          //print_r( $category['nume_categorie']."<br>" ) ;
          echoCategoryButton( $category['nume_categorie'] );
        }
      }

      ?>

      <!-- <button onclick="displayCategory()">Display all accounts</button> -->
    </div>

      <div class="center column" src="accounts.php">
        <?php 
        if(isset($_SESSION['canAddAccount']) && $_SESSION['canAddAccount'] == true)
        {
        ?>
        <button onclick="location.href = 'new_account.php';" id="addSite" type="button" class="buttonReversed middle innerButton"><b>Add new account</b></button>
        
        <?php
            ;}
        
          if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['usageOrder']))
          {
            $accounts = getAccounts('usage');  
          }
          else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['strengthOrder']))
          {
            $accounts = getAccounts('strength');
          }
          else
          {
            $accounts = getAccounts('none');
          }

          foreach($accounts['records'] as $account) { 
            echoAccount($account);
          }
        ?>
       
    </div> <!--accounts-->
</div> <!-- grid container -->

</body>
</html>
