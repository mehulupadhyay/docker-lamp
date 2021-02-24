<?php
  
  session_start();
  require_once "config.php";

  if(!isset($_SESSION['loggedin']) && !isset($_SESSION['admin'])) {
    header('LOCATION:index.php'); die();
  }

  if($_POST['update'] == 'users') {

    $sql = "UPDATE users SET project = " . $_POST['project'] . " WHERE uid = " . $_POST['uid'];

    if ($mysqli->query($sql)) {
      $_SESSION['project'] = $_POST['project'];
          exit;
        
    } else {
      echo "Error deleting record: " . $mysqli->error();
    }
  }
  if($_POST['update'] == 'buffer') {
    $sql = "UPDATE users SET buffer = " . $_POST['buffer'] . " WHERE uid = " . $_POST['uid'];

    if ($mysqli->query($sql)) {
      $_SESSION['buffer'] = $_POST['buffer'];
          exit;
        
    } else {
      echo "Error deleting record: " . $mysqli->error();
    }
  }
  
  
  $mysqli->close();
?>