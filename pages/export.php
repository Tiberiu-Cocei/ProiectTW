<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    include_once '../includes/exportFile.php';

    $data = null;
    $extensie = null;
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exportCSV']))
    {
        $data = export('csv');
        $extensie = '.csv';
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exportJSON']))
    {
        $data = export('json');
        $extensie = '.json';
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exportXML']))
    {
        $data = export('xml');
        $extensie = '.xml';
    }
    if($data !== null) {
        $file = "exportfile".$_SESSION['username'].$extensie;
        $myfile = fopen($file, "w");
        fwrite($myfile, $data);
        fclose($myfile);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export</title>
    <link rel="stylesheet" href="../public/css/loginStyles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>
<body>
<div class="outer middle inner">
    <h1 class="text-center"><u><b>Export data</b> </u></h1>
    <div class="text-center">
    </div>
    <div class=" text-center center">
      <form action="export.php" method="post">
        <button name="exportCSV" type="submit" style="margin-top:135px;"
                class="button middle innerButton"><b>Export data in CSV format</b></button>
        <button name="exportJSON" type="submit"
                class="button middle innerButton"><b>Export data in JSON format</b></button>
        <button name="exportXML" type="submit"
                class="button middle innerButton"><b>Export data in XML format</b></button>
        <button onclick="location.href = 'main_page.php';" id="resetPassword" type="button"
                class="button middle innerButton"><b>Return</b></button>
      </form>
    </div>
</div>

</body>
</html>
