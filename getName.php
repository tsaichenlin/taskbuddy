<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
if(isset($_SESSION['name'])){
  echo $_SESSION['name'];
}else{
  echo "No Name Found";
}
}
?>