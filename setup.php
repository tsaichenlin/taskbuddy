<?php
  $databaseFile = "database.db";
  try{
    $pdo = new PDO("sqlite: ".$databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $userTable = "CREATE TABLE IF NOT EXISTS users(
      user_id INTEGER PRIMARY KEY AUTOINCREMENT,
      username Text,
      password Text,
      full_name Text
    )";
    $taskTable = "CREATE TABLE IF NOT EXISTS tasks(
      user_id INT(6),
      task_name Text,
      due_date Date,
      progress INTEGER
    )";
    $pdo->exec($userTable);
    $pdo->exec($taskTable);
    echo "Success: Database Created!<br>";
    $fetch = $pdo->prepare("SELECT * FROM users");
    $fetch->execute();
    $table = $fetch->fetchAll(PDO::FETCH_ASSOC);
    echo var_dump($table);
    $fetch = $pdo->prepare("SELECT * FROM tasks");
    $fetch->execute();
    $table = $fetch->fetchAll(PDO::FETCH_ASSOC);
    echo var_dump($table);

  }catch(PDOException $e){
    echo "Error: ".$e->getMessage();
  }
?>
