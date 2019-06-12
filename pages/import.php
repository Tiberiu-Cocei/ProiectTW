<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    include_once '../includes/apiCall.php';
    function import($fileName){
      $data_array =  array(
        "id_utilizator" => $_SESSION['id_utilizator'],
        "nume_fisier" => $fileName
    );

      $call = ApiCall('POST', 'http://localhost/TWPM/api/import/import.php', json_encode($data_array));
      return $call;
    }

    $data = null;

    if(isset($_FILES['fileToUpload'])){
      if (($_FILES['fileToUpload']['name']!="")){
      	$target_dir = "upload/";
      	$file = $_FILES['fileToUpload']['name'];
      	$path = pathinfo($file);
      	$filename = $path['filename'];
      	$ext = $path['extension'];
      	$temp_name = $_FILES['fileToUpload']['tmp_name'];
      	$path_filename_ext = $target_dir.$filename.".".$ext;

        move_uploaded_file($temp_name,$path_filename_ext);
        $call = import($path_filename_ext);
        $call = explode('"message":"', $call);
        $data = $call[1];
        $data = substr($data, 0, -2);
        unlink($path_filename_ext);
       }
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1 class="text-center"><u><b>Import data</b> </u></h1>
    <div class="text-center">
    </div>
    <div class=" text-center center">
      <form action="import.php" method="post" enctype="multipart/form-data">
        <input style="margin-top:100px;" type="file" name="fileToUpload" id="fileToUpload">
        <h3> <?php if($data !==null) echo $data; ?> </h3>
        <input type="submit" value="Upload File" name="submit">
        <button onclick="location.href = 'main_page.php';" id="resetPassword" type="button"
                class="button middle innerButton"><b>Return</b></button>
      </form>
    </div>
</div>

</body>
</html>
