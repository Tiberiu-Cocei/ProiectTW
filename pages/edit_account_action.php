<?php
 session_start(); 
include_once '../includes/apiCall.php';
include_once '../includes/unsetCookies.php';

function setResponseCookie($response)
{
    $locationForEditAccountPage = "./edit_account.php/?id_account_to_be_edited=?". $id_cont ;
    setcookie("response", $response, time() + 360, "/");
    header( $locationForEditAccountPage );
}

if(isset($_POST['returnToMainPage']))
{
    $_SESSION['id_account_to_be_edited'] = 0;
    setcookie('response', null, time()-1, '/');
    header("Location: ./main_page.php");
}
else
{
    if(isset($_POST['tryToEdit']))
    {
        //aici ar trebui sa validam si daca userul conectat are acel id 


        $siteName      = $_POST['siteNameEdited']; 
        $username      = $_POST['usernameEdited']; 
        $password      = $_POST['passwordEdited'];
        $address       = $_POST['addressEdited'];
        $comments      = $_POST['commentsEdited'];
        $reminderDate  = $_POST['reminderDateEdited'];
        
        setcookie("siteNameEdited", $_POST['siteNameEdited'], time() + 360, "/"); //6 minute
        setcookie("usernameEdited", $_POST['usernameEdited'], time() + 360, "/");
        setcookie("addressEdited",  $_POST['addressEdited'],  time() + 360, "/");
        setcookie("commentsEdited", $_POST['commentsEdited'], time() + 360, "/");
        setcookie("reminderDateEdited", $_POST['reminderDateEdited'], time() + 360, "/");

        //apelam apiul ce ar trebui sa editeze. El va face si validarile necesare
        $accountUpdateApi = 'http://localhost/TWPM/api/account/update.php'; 
        
        $id_cont = $_SESSION['id_account_to_be_edited']; 

        $account_array =  array(
            "id_cont" => $id_categorie,
            "username"  => $username,
            "parola"  => $password,
            "adresa_site"  => $address,
            "nume_site"  => $siteName,
            "comentarii"  => $comments,
            "data_expirare"  => $reminderDate
        );

        $make_call = ApiCall('POST', $accountUpdateApi, json_encode($account_array));
        
        $response = json_decode($make_call, true);

        if(!empty($response['message']))
            setResponseCookie( $response['message'] );
        else 
            setResponseCookie( "Account successfully created.");

        header("Location: ".$locationForEditAccountPage);
    }

    header("Location: ./main_page.php" ); 
}

?>