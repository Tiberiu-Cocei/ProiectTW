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
        <button onclick="location.href = '#exportCSV.php';" id="exportCSV" type="button" style="margin-top:135px;"
                class="button middle innerButton"><b>Export data in CSV format</b></button>
        <button onclick="location.href = '#exportJSON.php';" id="exportJSON" type="button"
                class="button middle innerButton"><b>Export data in JSON format</b></button>
        <button onclick="location.href = '#exportXML.php';" id="exportXML" type="button"
                class="button middle innerButton"><b>Export data in XML format</b></button>
        <button onclick="location.href = 'index.php';" id="resetPassword" type="button"
                class="button middle innerButton"><b>Return</b></button>
    </div>
</div>

</body>
</html>