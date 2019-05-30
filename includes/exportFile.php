<?php
  include_once 'apiCall.php';
  function export($type){
    $data;
    if($type === 'json')
      $data = apiCall('GET', 'http://localhost/TWPM/api/export/to_json.php?id_utilizator='.$_SESSION['id_utilizator']);
  }
?>
