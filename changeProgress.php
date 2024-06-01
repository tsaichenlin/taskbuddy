<?php
session_start();
$databaseFile = "database.db";
try{
  $pdo = new PDO("sqlite: ".$databaseFile);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Error: ".$e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $progress = intval($_POST['progress']);
  $sql = "UPDATE tasks SET progress = :progress WHERE task_name= :name";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':progress', $progress, PDO::PARAM_INT);
  $stmt->bindParam(':name', $_POST['taskName'], PDO::PARAM_STR);

  if($stmt->execute()){
    echo "Task Updated";
  }else{
    echo "Fail to Update Task";
  }
}?>
