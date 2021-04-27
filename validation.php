<?php
#Validating all data from user_input 

function test_input($data) 
  {
    $data = strtolower($data);
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>