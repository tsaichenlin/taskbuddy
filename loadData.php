<?php
session_start();
$databaseFile = "database.db";
try{
  $pdo = new PDO("sqlite: ".$databaseFile);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $fetch = $pdo->prepare("SELECT * FROM tasks WHERE user_id=?");
  $fetch->execute([$_SESSION["user_id"]]);
  $result = $fetch->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
  echo "Error: ".$e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if($result){
    foreach($result as $row){
      $task = array($row["task_name"],$row["due_date"],$row["progress"]);
      echo json_encode($task);
    }
  }else{
    echo "Error";
  }
}

?>