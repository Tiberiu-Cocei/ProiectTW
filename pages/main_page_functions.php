<?php
include_once '../includes/unsetCookies.php';

//afiseaza categoriile de conturi 
//si o coloana cu cele 3 moduri de filtrare a conturilor. 
function showCategoriesColumn()
{
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
    $categoriesApi = 'http://localhost/TWPM/api/category/get_by_user_id.php?id_utilizator='.$data; 

    $make_call = ApiCall('GET', $categoriesApi);

    $response = json_decode($make_call, true);

    $allCategories = array(); 

    echo "<form method=\"POST\">";
    foreach($response['records'] as $category) {
      echoCategoryButton( $category['nume_categorie'] );                            //adaugam butonul categoriei 
      $allCategories += [$category['nume_categorie'] => $category['id_categorie']]; //punem numele ( categoriei + id ) intr-o variabila
    }
    echo "</form>"; 

    //punem intr-un cookie toate categoriile disponibile 
    setcookie("allCategoriesCookie", serialize($allCategories), time() + 3600);  
  }
}

//afiseaza conturile din cookiul cu toate conturile disponibile de afisat pentru utilizatorul curent. 
//si butoanele aditionale acestei coloane
function showAccountColumn() 
{
  //vedem ce e de afisat. 
  verificaDacaAmApasatUnButon(); 
  
  $details = ""; 
  if(isset($_COOKIE['addAccountButton']))
    $details = $details. "<button onclick=\"location.href = 'new_account.php';\" id=\"addSite\" type=\"button\" class=\"buttonReversed middle innerButton\">
    <b>Add new account</b></button><br>"; 
  
  if(isset($_COOKIE['allAccountsToShowCookie']))
    $accountsToShow = unserialize($_COOKIE['allAccountsToShowCookie'], ["allowed_classes" => false]);
  else
  {
    $accountsToShow = array(); 
    $accountsToShow['records'] = array();
  }
  $details = $details. getAccountsDetailsInString($accountsToShow); 

  echo $details; 
}

function verificaDacaAmApasatUnButon()
{
  //verificam daca e selectata o categorie -> punem in cookie ce conturi ii corespund ei 
  if(isset($_COOKIE['selectedCategoryID']))
  {
    $allAcountsForThisCategory = getAccountsByCategory($_COOKIE['selectedCategoryID']); 
    setcookie("allAccountsToShowCookie", serialize($allAcountsForThisCategory), time() + 3600, '/TWPM/pages');
  }

  if(isset($_COOKIE['allCategoriesCookie']))
    $allCategories = unserialize($_COOKIE['allCategoriesCookie'], ["allowed_classes" => false]); //luam toate categoriile afisate pe pagina
  else 
  {
    $allCategories = array(); 
    $allCategories['records'] = array();
  }

  if(isset($_POST['usageOrder']) || isset($_POST['strengthOrder']) || isset($_POST['showCategories']))
  {
    setcookie("allAccountsToShowCookie", null, -1, '/TWPM/pages');
    setcookie("addAccountButton", null, -1, '/TWPM/pages');

    if(isset($_POST['usageOrder']))
      $allAccountsToShow = getAccountsByType('usage'); 
    else if (isset($_POST['strengthOrder']))
      $allAccountsToShow = getAccountsByType('strength'); 
    else
      {
        $allAccountsToShow = array();
        $allAccountsToShow['records'] = array();
      } 
    setcookie("allAccountsToShowCookie", serialize($allAccountsToShow), time() + 3600, '/TWPM/pages');
  }
  else 
  {
    //pentru fiecare cont verificam daca a fost selectat 
    foreach($allCategories as $nume_buton => $id_buton) 
    {
      //setcookie("allAccountsToShowCookie", null, -1, '/TWPM/pages');
      //setcookie("addAccountButton", null, -1, '/TWPM/pages');

      if(isset($_POST[$nume_buton]))
        {
          setcookie("allAccountsToShowCookie", serialize(getAccountsByCategory($id_buton)), time() + 3600);
          setcookie("addAccountButton", 1, time() + 3600, '/TWPM/pages'); 
        }
    }
  }
}
?>

<script>
function categoryButtonClick(category_name)
{
  allAcountsForThisCategory = getAccountsByName(category_name); 
  setcookie("allAccountsToShowCookie", serialize($allAcountsForThisCategory), time() + 3600);
  document.write(getAccountsDetailsInString(getAccountsByCategory(category_name))); 
}
</script>


<?php
//primeste un id de cont si returneaza parola corespunzatoare celui cont
function getPasswordForContID($id_cont)
{
  $accountsApi = 'http://localhost/TWPM/api/account/show_password.php?id_cont='. $id_cont .'&id_utilizator=' . $_COOKIE['userID'];

  $make_call = ApiCall('GET', $accountsApi);

  return  $make_call; 
}


function echoCategoryButton($category_name)
{
  //$buttonSettings = "<button onclick=\"categoryButtonClick($category_name)\"   class=\"button middle innerButton\" > $category_name </button>"; 
  $buttonSettings = "<input type=\"submit\" name=\"$category_name\" value=\"$category_name\" class=\"button middle innerButton\" onClick=\"document.location.href='./main_page.php'\"><br>";
  echo $buttonSettings;
}

function getAccountsDetailsInString($accountsToShow = array())
{
  $details = ""; 

  if(isset($accountsToShow) && isset($accountsToShow['records']))
  {
    foreach($accountsToShow['records'] as $account) 
    { 
      $details = $details. getSingleAccountDetailsInString($account);
    }
  }
  return $details;
}


function getSingleAccountDetailsInString($account, $showPassword = false)
{
  $plainPassword = getPasswordForContID($account['id_cont']); 
  $password = $plainPassword; 

  // if($showPassword == false)
  // {
  //   $length = strlen($password);
  //   $password = "";
  //   $password = str_pad($password, $length, "*");
  //}

  $details = "<div class=\"textWrapper\">"; 
  $details = $details. "<h2>Username: ". $account['username'] . "</h2>\n";
  $details = $details. "<h2>Password: ". $password. "</h2>\n"; 
  $details = $details. "<input type=\"text\" id=\"passwordField\"><br><br>\n";  

  $details = $details. "<h2>Web adress: ". $account['adresa_site'] . "</h2>\n"; 
  $details = $details. "<h2>Web adress: ". $account['nume_site'] . "</h2>\n"; 
  $details = $details. "<h2>Comments: ". $account['comentarii'] . "</h2>\n"; 
  $details = $details. "<h2>Password safety: ". $account['putere_parola'] . "</h2>\n"; 
  $details = $details. "<h2>Reset reminder: None</h2>\n"; 
  $details = $details. "<h2>Add date: ". $account['data_adaugare'] . "</h2>\n"; 
  $details = $details. "<h2>Expire date: ". $account['data_expirare'] . "</h2>\n";

  // $details = $details. "<input type=\"submit\"  onclick=\"onclick=\"functiaDeAfisare( e," . $account['id_cont'] ." ) name=\"Showpassword".$account['id_cont']."\" value=\"Show password\"  style=\"font-weight: bold;\" class=\"button\">\n" ;
  // $details = $details . "<button onclick=\"functiaDeAfisare( e," . $account['id_cont'] ." )\"> Show Password </button> \n";
  // $details = $details . "<button onclick=\"myFunction( e, ".$plainPassword. ")\"> Copy Password </button>\n"; 
  // $details = $details. "<form method=\"POST\"><input type=\"submit\" name=\"Showpassword".$account['id_cont']."\" value=\"Show password\" style=\"font-weight: bold;\" class=\"button\">" ;
  // $details = $details. "<form method=\"POST\" action=\"#\"><input type=\"submit\" name=\"editAccountInfo".$account['id_cont']."\" value=\"Edit account info\" style=\"font-weight: bold;\" class=\"button\">" ;
  // $details = $details. "<input type=\"submit\" name=\"deleteId".$account['id_cont']."\" value=\"Delete entry\" style=\"font-weight: bold;\" class=\"button\">" ;
  
  $details = $details. "<button onclick=\"location.href = 'edit_account.php\?id_account_to_be_edited=". $account['id_cont'] ."'\" id=\"addSite\" class=\"button\"> <b>Edit account details</b></button><br>"; 

  $details = $details. "</div><br>";
  
  // $details = $details. "<form method=\"POST\" action=\"#\"><input type=\"submit\" name=\"editAccountInfo".$account['id_cont']."\" value=\"Edit account info\" 
  //             style=\"font-weight: bold;\" class=\"button\">\n" ;
  //\?id_account_to_be_edited=".$account['id_cont']. 
  
  echo $details; 
}

function getAccountsByType($orderType)
{
  if($orderType == 'strength' || $orderType == 'usage')
  {
    $accountsApi = 'http://localhost/TWPM/api/account/get_by_'.$orderType.'.php?id_utilizator='.$_COOKIE['userID']."'"; 

    $make_call = ApiCall('GET', $accountsApi, json_encode($_COOKIE['userID']));

    return json_decode($make_call, true);
  }

  $response['records'] = array();

  return $response;
}

function getAccountsByName($category_name)
{
  $allCategories = unserialize($_COOKIE['allCategoriesCookie'], ["allowed_classes" => false]); //luam toate categoriile afisate pe pagina

  foreach($allCategories as $key_category_name => $value_category_id)  //si o alegem pe cea selectata
  {
    if($key_category_name == $category_name)
    {
      $accounts = getAccountsByCategory($value_category_id); 
    }
  }
  return $accounts; 
}

function getAccountsByCategory($id_categorie)
{
  $accountsApi = 'http://localhost/api/account/get_by_category.php?id_categorie='.$id_categorie; 

  $make_call = ApiCall('GET', $accountsApi, json_encode($id_categorie));

  return json_decode($make_call, true);
}

?>
