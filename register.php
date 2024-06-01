
<?php
session_start();
$databaseFile="database.db";
try{
    $pdo = new PDO("sqlite: ". $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database";
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    if($check->fetchColumn()>0){
      echo "Username Already Exist";
    }else{
      $sql = "INSERT INTO users (username, password ,full_name) VALUES (?,?,?)";
      $stmt = $pdo->prepare($sql);
      if ($stmt->execute([$username,$password,$name]) === TRUE) {
        $s = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $s->execute([$username]);
        $result = $s->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['name'] = $name;

        echo "User registered successfully!";
  
      } else {
          echo "Error registering user: " . $conn->error;
      }
    }
   
}
?>