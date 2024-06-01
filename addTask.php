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
  $task = $_POST["taskName"];
  $due = $_POST["dueDate"];
  $sql = "INSERT INTO tasks(user_id,task_name,due_date,progress) VALUES(?,?,?,?)";
  $stmt = $pdo->prepare($sql);
  if($stmt->execute([$_SESSION['user_id'],$task,$due,0])){
    echo "Task Added";
  }else{
    echo "Fail to Add Task";
  }
}

?>
