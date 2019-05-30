<?php

include_once 'password_strength.php';

function isValid($data) {
  if(empty($data->username) || empty($data->parola) || empty($data->nume) || empty($data->prenume) || empty($data->email)) return "Missing information field.";

  if(1 === preg_match('/[0-9]/', $data->nume)) return "First name cannot contain numbers";
  if(1 === preg_match('/[0-9]/', $data->prenume)) return "Last name cannot contain numbers.";
  if(0 === preg_match('/\@/', $data->email) || 0 === preg_match('/\./', $data->email)) return "Invalid email address.";
  if(strlen($data->parola) < 8) return "Password length must exceed 8 characters.";
  if(determineStrength($data->parola) < 6) return "Password is too weak. Try adding special and uppercase characters.";
  return "Valid";
}
?>
