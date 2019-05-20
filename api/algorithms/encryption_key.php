<?php
function generateEncryptionKey($username){
  $returnString = "";
  $returnString .= $username;
  $returnString .= "encryptionkey";
  $returnString = str_shuffle($returnString);
  return $returnString;
}
?>
