<?php
  include_once 'apiCall.php';
  function export($type){
    $data = "";
    if($type === 'json')
      $data = apiCall('GET', 'http://localhost/TWPM/api/export/to_json.php?id_utilizator='.$_SESSION['id_utilizator']);
    else if($type === 'xml')
      $data = apiCall('GET', 'http://localhost/TWPM/api/export/to_xml.php?id_utilizator='.$_SESSION['id_utilizator']);
    else if($type === 'csv')
      $data = apiCall('GET', 'http://localhost/TWPM/api/export/to_csv.php?id_utilizator='.$_SESSION['id_utilizator']);
    return $data;
  }
?>
