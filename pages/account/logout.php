<?php
    session_start(); 
    include_once '../../includes/unsetCookies.php';
    unsetCookieForLogout(); 
    session_destroy();
    header("Location:./Login.php");
?>
