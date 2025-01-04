<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "ai_training" ;


try {
  $conn = new PDO("mysql:localhost=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn -> setAttribute(PDO::ATTR_EMULATE_PREPARES , false);
//   echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>


