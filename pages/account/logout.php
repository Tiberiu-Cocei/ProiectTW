<?php
    session_start(); 
    include_once '../../includes/unsetCookies.php';
    unsetCookiesForLogout(); 
    session_destroy();
    header("Location:./Login.php");
?>
