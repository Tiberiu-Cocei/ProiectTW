<?php
    include_once '../../includes/apiCall.php';
    include_once '../../includes/unsetCookies.php';
    
    function setResponseCookie($response)
    {
        setcookie("response", $response, time() + 360, "/");
        header("Location: ./changePassword.php");
    }
    
    unsetCookie("response"); 

    if(isset($_POST['returnToMainPage']))
    {
        setResponseCookie("return");
    }
    else if(isset($_POST['tryToChangePassword']))
    {
        $oldPassword        = $_POST['oldPassword'];
        $newPassword        = $_POST['newPassword'];
        $newPasswordConfirm = $_POST['newPasswordConfirm'];

        if(empty($oldPassword) || empty($newPassword) || empty($newPasswordConfirm))
        {
            setResponseCookie("Empty fields existent");
        }
        elseif($newPassword != $newPasswordConfirm)
        {
            setResponseCookie("New password doesn't match");
        }
        else
        {
            $data_array =  array(
                "username" => $_COOKIE['usernameConnected'],
                "parola" => $oldPassword
            ); 

            $make_call = ApiCall('POST', 'http://localhost/TWPM/api/user/loginPentruParolaCriptata.php', json_encode($data_array));

            $response = json_decode($make_call, true);
            $data     = $response['message'];

            if($data === "Successfully logged in." || $data === null) //avem parola corecta
            {
                $hashedePwd = password_hash($newPassword, PASSWORD_DEFAULT);

                $data_array = array(
                    "username" => $_COOKIE['usernameConnected'],
                    "parola" => $hashedePwd,
                    "nume" => null,
                    "prenume" => null,
                    "email" => null,
                );

                $make_call = ApiCall('POST', 'http://localhost/TWPM/api/user/update.php', json_encode($data_array));

                setResponseCookie("Succesfully changed");
            }
            else
                setResponseCookie("Wrong old password for ". $_COOKIE['usernameConnected']); 
        }
    }

    header("Location: ./changePassword.php");
?>
