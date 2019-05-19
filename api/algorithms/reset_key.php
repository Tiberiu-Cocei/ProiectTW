<?php
function generateResetKey(){
  $length = 12;
  $reset_key = "";
  $i;
  $repeating_type = 50;

  for($i = 0; $i<=$length; $i++){
    $type = rand(1, 100);
    if($type < $repeating_type) {

      $nr = rand(1,10);
      $reset_key .= $nr-1;

      if($repeating_type > 50) $repeating_type = 50;
      else $repeating_type-=10;

    } else {

      $caseType = rand(1,2);
      $letter = rand(1,26);

      if($caseType === 1) $reset_key .= chr(64+$letter);
      else $reset_key .= chr(96+$letter);

      if($repeating_type < 50) $repeating_type = 50;
      else $repeating_type+=10;
    }
  }
  return $reset_key;
}
?>
