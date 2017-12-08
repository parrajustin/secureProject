<?php
  session_start();
  ob_start();

  if (!isset($_SESSION['username'])) {
    header ('location: ../index.php');
  }

  // setup params
  $host='localhost';
  $user='root';
  $password='';
  $database='project';
  $table='parts';
  $conn = new mysqli($host, $user, $password, $database);

  echo json_encode($_POST);
?>