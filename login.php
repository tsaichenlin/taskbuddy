<?php
$databaseFile="database.db";
session_start();
try{
    $pdo = new PDO("sqlite: ". $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database";
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = $pdo->prepare("SELECT password, user_id, full_name FROM users WHERE username = ?");
    $check->execute([$username]);
    $result = $check->fetch(PDO::FETCH_ASSOC);
    if($result){
      if(password_verify($password, $result['password'])){
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['name'] = $result['full_name'];
        echo "Success";
      }else{
        echo "Incorrect Password";
      }
    }else{
      echo "Not Registered";
    }
}
?>