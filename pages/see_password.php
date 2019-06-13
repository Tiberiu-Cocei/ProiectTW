<?php 
    session_start(); 
    include_once '../includes/apiCall.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>See plain password</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
</head>
<body>

<?php 
    if( isset($_GET['id_utilizator']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['contor_utilizari']))
    {
        $stringApi = 'http://localhost/TWPM/api/account/password_decrypt.php/?password='
                    . $_GET['password'] . "&id_utilizator=". $_GET['id_utilizator']; 

        $make_call = ApiCall('GET', $stringApi);
  
        $response  = json_decode($make_call, true);

        $password = $response; 

        ?>

        <div class="outer middle inner">
        <h1 class="text-center"><u><b>Use password: </b></u></h1>
            <div class="text-center center">
                <form method="POST">
                    <h2>Username: <?php echo $_GET['username'] ?> </h2><br>

                    <h2>Password: <?php echo $password ?></h2><br>

                    <h2>You used it for <?php echo $_GET['contor_utilizari'] ?> times</h2><br>



                    <button type="submit" name="returnToMainPage"   class="button middle innerButton">  <b>Return to main page</b></button>
                </form>
            </div>
        </div>
    <?php 
    }
    else 
    {
        echo "Unde esti, draga?"; 
    }
    
    if(isset($_POST['returnToMainPage']))
    {
        header("Location: ./main_page.php" ); 
    }
    
    ?>

</body>
</html>