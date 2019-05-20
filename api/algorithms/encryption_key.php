<?php
function generateEncryptionKey($username){
  $returnString = "";
  $returnString .= $username;
  $returnString .= "key";
  $returnString = str_shuffle($returnString);
  return $returnString;
}
?>
