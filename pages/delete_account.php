<?php 
    session_start();
    include_once '../includes/apiCall.php';
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete account confirmation</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
</head>
<body>

<?php 
    if(isset($_GET['id_account_to_be_deleted']))
    {
        ?>

        <div class="outer middle inner">
        <h1 class="text-center"><u><b>Are you sure you want to delete this account?</b></u></h1>
            <div class="text-center center">
                <form method="POST">
                    <button type="submit" name="deleteConfirmation" class="button middle innerButton">  <b>Yes, delete it.</b></button>
                    <button type="submit" name="returnToMainPage"   class="button middle innerButton">  <b>Return to main page</b></button>
                </form>
            </div>
        </div>
        
        <?php 

        if(isset($_POST['returnToMainPage']))
        {
            header("Location: ./main_page.php");
        }
        else if(isset($_POST['deleteConfirmation']))
        {
            $deteleAccountApi = 'http://localhost/TWPM/api/account/delete.php';

            $deleteAccountArray = array(); 
            $deleteAccountArray['id_cont'] = $_GET['id_account_to_be_deleted'];

            $make_call = ApiCall('POST', $deteleAccountApi, json_encode($deleteAccountArray));

            $response = json_decode($make_call, true);

            echo $response; 

            header("Location: ./main_page.php");
        }
    }
    ?>

</body>
</html>