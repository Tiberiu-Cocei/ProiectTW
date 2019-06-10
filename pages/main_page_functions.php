<?php

include_once '../includes/unsetCookies.php';

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
    $_SESSION['id_utilizator'] = $data; 
    $categoriesApi = 'http://localhost/TWPM/api/category/get_by_user_id.php?id_utilizator='.$data; 

    $make_call = ApiCall('GET', $categoriesApi);

    $response = json_decode($make_call, true);

    $allCategories = array(); 

    // echo "<form method=\"POST\">";
    foreach($response['records'] as $category) {
      echoCategoryButton( $category['nume_categorie'] );                            //adaugam butonul categoriei 
      $allCategories += [$category['nume_categorie'] => $category['id_categorie']]; //punem numele ( categoriei + id ) intr-o variabila
    }

    setcookie("allCategoriesCookie", serialize($allCategories), time() + 3600);
  }
}

//primeste un sir de conturi ( dupa forma arrayului din api, cu ['records'] ) pe care il va afisa
//daca nu primeste parametru, inseamna ca nu se vor afisa conturi
function showAccountColumn($accountsToShow = array()) 
{
  //doar in cazul in care suntem in interiorul unei categorii se va pune la inceput si butonul de adaugare de cont, altfel nu (fiindca nu vom sti in ce categorie sa punem contul adaugat..)
  tryShowAddAccountButton(); 

  if(isset($accountsToShow) && isset($accountsToShow['records']))
  {
    foreach($accountsToShow['records'] as $account) 
    { 
      echoAccount($account);
    }
  }
}

//verifica daca e setata categoria si adauga un buton de adaugare de cont daca este setata
function tryShowAddAccountButton()
{
  if(isset($_COOKIE['selectedCategoryID']))//(isACategorySelected($allCategories))
  {
     $localButton = "<button onclick=\"location.href = 'new_account.php';\" id=\"addSite\" type=\"button\" class=\"buttonReversed middle innerButton\">
          <b>Add new account</b></button>"; 
     echo $localButton; 
  }
}

//primeste un id de cont si returneaza parola corespunzatoare celui cont
function getPasswordForContID($id_cont)
{
  $accountsApi = 'http://localhost/TWPM/api/account/show_password.php?id_cont='. $id_cont .'&id_utilizator=' . $_COOKIE['userID']; 
  
  $make_call = ApiCall('GET', $accountsApi);

  return  $make_call; 
}

function functionSetCategoryCookie($name)
{
  setcookie("selectedCategoryID", $name, time() + 3600, "/");
}
?>

<script>

function categoryButtonClick(category_name)
{
  <?php showAccountColumn(getAccountsByName("categoria1")); ?>
}
</script>

<?php
function echoCategoryButton($category_name)
{
  $buttonSettings = "<button onclick=\"categoryButtonClick($category_name)\"   class=\"button middle innerButton\" > $category_name </button>"; 
  //$buttonSettings = "<input type=\"submit\" name=\"$category_name\" value=\"$category_name\" class=\"button middle innerButton\" onClick=\"document.location.href='./main_page.php'\"><br>";
  echo $buttonSettings;
}

function echoAccount($account, $showPassword = false)
{
  $plainPassword = getPasswordForContID($account['id_cont']); 
  $password = $plainPassword; 

  if($showPassword == false)
  {
    $length = strlen($password); 
    $password = ""; 
    $password = str_pad($password, $length, "*");
  }

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

  $details = $details. "<form method=\"POST\"><input type=\"submit\"  onclick=\"onclick=\"functiaDeAfisare( e," . $account['id_cont'] ." ) name=\"Showpassword".$account['id_cont']."\" value=\"Show password\" 
  style=\"font-weight: bold;\" class=\"button\">\n" ;

 $details = $details . "<button onclick=\"functiaDeAfisare( e," . $account['id_cont'] ." )\"> Show Password </button> \n";
 
 $details = $details . "<button onclick=\"myFunction( e, ".$plainPassword. ")\"> Copy Password </button>\n"; 

  $details = $details. "<form method=\"POST\" action=\"#\"><input type=\"submit\" name=\"editAccountInfo".$account['id_cont']."\" value=\"Edit account info\" 
              style=\"font-weight: bold;\" class=\"button\">\n" ;

  $details = $details. "<input type=\"submit\" name=\"deleteId".$account['id_cont']."\" value=\"Delete entry\" 
  style=\"font-weight: bold;\" class=\"button\">\n" ;

   
  $details = $details. "</div>"; 
  echo $details; 
}


function getAccountsByType($orderType)
{
  if($orderType == 'strength' || $orderType == 'usage')
  {
    $accountsApi = 'http://localhost/TWPM/api/account/get_by_'.$orderType.'.php?id_utilizator='.$_SESSION['id_utilizator']."'"; 

    $make_call = ApiCall('GET', $accountsApi, json_encode($_SESSION['id_utilizator']));

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
