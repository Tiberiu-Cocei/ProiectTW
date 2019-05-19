<?php
function generateSafePassword(){
  $length = rand(14,18);
  $reset_key = "";
  $i;
  $repeating_type = 50;

  for($i = 0; $i<=$length; $i++){
    $type = rand(1, 100);
    if($type < $repeating_type) {
      $nrSymb = rand(1,2);

      if($nrSymb === 1) {
        $nr = rand(1,10);
        $reset_key .= $nr-1;
      }
      else {
        $nr = rand(1,5);
        if($nr === 1) $reset_key .= chr(33);
        else if($nr < 6) $reset_key .= chr(33+$nr);
        else $reset_key .= chr(64);
      }

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
