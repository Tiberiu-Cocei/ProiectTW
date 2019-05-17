<?php
    $connection = mysqli_connect("localhost", "root", "", "twpm"); 
    if(mysqli_connect_errno())
    {
        echo "fail to connect".mysqli_connect_error(); 
    }
    

?>