<?php
  session_start();
  ob_start();

  if (!isset($_SESSION['username'])) {
    header ('location: ../index.php');
  }

  // setup params
  require_once('../db.php');
  $db = new dbClass();

  // setup params
  $conn = $db->getConnection();

  echo json_encode($_POST);
?>