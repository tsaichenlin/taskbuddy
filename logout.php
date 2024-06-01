<?php
$databaseFile="database.db";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" ){
    if(session_destroy()){
      echo "Success";
    }else{
      echo "Failed";
    }
}
?>

