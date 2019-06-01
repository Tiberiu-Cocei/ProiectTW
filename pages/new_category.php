<?php 
    session_start(); 
    include_once '../includes/apiCall.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Category</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
</head>
<body>
<div class="outer middle inner">
<h1 class="text-center"><u><b>Add new category</b> </u></h1>
    <div class="text-center">
    </div>
    <div class="text-center center">
        <BR><BR><BR><BR>
        <form method="POST">
            <h3>
            <input type="text" name="newCategory" placeholder="category name" class="form-control dataField" style="margin-top:35px;"></h3>
            <input type="submit" name="tryToCreate" value="Create new category" style="font-weight: bold;" class="button middle innerButton">
            <button onclick="location.href = 'main_page.php';" type="button" class="button middle innerButton"><b>Cancel</b></button>
        </form>

    <?php
    if(isset($_POST['tryToCreate']))
    {
        if(!isset($_POST['newCategory']) || ($_POST['newCategory']) == null )
            echo "<h3>Enter a category name</h3>";
        else
        {
            if(!ctype_alnum (str_replace(' ', 'A', $_POST['newCategory']))) //alfanum or contains spaces
            {
                echo "<h3>Choose alfa-numeric name for your category</h3>"; 
            }
            else
            {
                $category_name = $_POST['newCategory']; 

                //scoatem toate categoriile existente din BD ( Aici poate ar fi mai bine sa avem o lista globala cu toate categoriile ca sa nu facem accesari asa dese la bd)
                $categoriesApi = 'http://localhost/api/category/get_by_user_id.php?id_utilizator='.$_SESSION['id_utilizator']."'"; 

                $make_call = ApiCall('GET', $categoriesApi, json_encode($_SESSION['id_utilizator']));

                $response = json_decode($make_call, true);

                //verificam daca a noastra e existenta in BD sau e noua
                $inBD = false; 
                foreach($response['records'] as $category) {
                    if ($category_name == $category['nume_categorie']){
                        $inBD = true; 
                    }
                }

                if($inBD){
                    echo "<h3>Existent category name"; 
                }//daca nu e in BD incercam sa o adaugam
                else
                {
                    $categoriesApi = 'http://localhost/api/category/create.php'; 
                    $arguments =  array(
                        "id_utilizator"  => $_SESSION['id_utilizator'],
                        "nume_categorie" => $category_name
                    );
                    $make_call = ApiCall('POST', $categoriesApi, json_encode($arguments));

                    $response = json_decode($make_call, true);

                    echo "<h3>".$response['message']."</h3>"; 
                }
            }
        }
    } 
    ?>
</div>
</body>
</html>
