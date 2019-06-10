<?php

include_once '../includes/unsetCookies.php';

function echoCategoryButton($category_name)
{
  $buttonSettings = "<input type=\"submit\" name=\"$category_name\" value=\"$category_name\" class=\"button middle innerButton\"
  onClick=\"document.location.href='./main_page.php'\"><br>";
  echo $buttonSettings;
}

function getPasswordForContID($id_cont)
{
  $accountsApi = 'http://localhost/TWPM/api/account/show_password.php?id_cont='. $id_cont .'&id_utilizator=' . $_COOKIE['userID']; 
  
  $make_call = ApiCall('GET', $accountsApi);

  return  $make_call; 

}

function echoAccount($account, $showPassword = false)
{
  $password = getPasswordForContID($account['id_cont']); 

  if($showPassword == false)
  {
    $length = strlen($password); 
    $password = ""; 
    $password = str_pad($password, $length, "*");
  }

  $details = "<div class=\"textWrapper\">"; 
  $details = $details. "<h2>Username: ". $account['username'] . "</h2>";
  $details = $details. "<h2>Password: ". $password. "</h2>"; 
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

function passwordShow()
{
  


}

function getAccounts($orderType, $allCategories = array())
  {
    if($orderType == 'strength' || $orderType == 'usage')
    {
      $accountsApi = 'http://localhost/TWPM/api/account/get_by_'.$orderType.'.php?id_utilizator='.$_SESSION['id_utilizator']."'"; 
  
      $make_call = ApiCall('GET', $accountsApi, json_encode($_SESSION['id_utilizator']));

      unsetCookie("selectedCategoryID");

      return json_decode($make_call, true);
    }
    if($orderType = 'justSelectedCategory')
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
    unsetcookie("selectedCategoryID");

    $response['records'] = array();

    return $response;
  }

  function getAccountsByCategory($id_categorie)
  {
    $accountsApi = 'http://localhost/api/account/get_by_category.php?id_categorie='.$id_categorie; 

    $make_call = ApiCall('GET', $accountsApi, json_encode($id_categorie));

    return json_decode($make_call, true);
  }

  function isAnAccountSelectedToDelete($allAccounts)
  {
    if(isset($allAccounts))
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
  }



?>