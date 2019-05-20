<?php
function determineStrength($parola) {
  $nota = 0;
  //nota pentru marime: 0-4
  if(strlen($parola)<10) $nota += 1;
  else if(strlen($parola) < 13) $nota += 2;
  else if(strlen($parola) < 16) $nota += 3;
  else $nota += 4;
  //nota pentru aparenta simboluri: 0-2
  if (1 === preg_match('/[^£$%&*()}{@#~?><>|=_+]/', $parola)) $nota += 2;
  //nota pentru caractere cu majuscula 0-2
  if (1 === preg_match('/[A-Z]/', $parola)) $nota += 2;
  //nota pentru aparenta cifre 0-2
  if (1 === preg_match('/[0-9]/', $parola)) $nota += 2;

  return $nota;
}
?>
