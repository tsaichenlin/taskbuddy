<?php
session_start();
$databaseFile = "database.db";

try {
    $pdo = new PDO("sqlite: ".$databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*$fetch = $pdo->prepare("SELECT task_id FROM tasks WHERE user_id = ?");
    $fetch->execute([$_SESSION["user_id"]]);
    $tasks = $fetch->fetchAll(PDO::FETCH_ASSOC);*/

 
    $deleteTask = $pdo->prepare("DELETE FROM tasks WHERE user_id = ?");
    if (!$deleteTask->execute([$_SESSION['user_id']])) {
        echo "Error deleting task with ID: " . $_SESSION['user_id'];
    }

    $deleteUser = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    if ($deleteUser->execute([$_SESSION["user_id"]])) {
        session_destroy();
        echo "Success";
    } else {
        echo "Error deleting user";
    }
} catch(PDOException $e) {
    echo "Error: ".$e->getMessage();
}
?>
