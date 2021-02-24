<?php
    session_start();
    require_once "config.php";
    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['admin'])) {
        header('LOCATION:index.php'); die();
    }
  
    
  $sql = "DELETE FROM users WHERE uid='" . $_GET["id"] . "'";
  
  if ($mysqli->query($sql)) {
      header("location:users.php");
        exit;
      
  } else {
      echo "Error deleting record: " . $mysqli->error();
  }
  $mysqli->close();
?>