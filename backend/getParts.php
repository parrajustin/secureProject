<?php
  session_start();

  // setup params
  $host='earth.cs.utep.edu';
  $user='ecorral6';
  $password='YgS&yMn&';
  $database='ecorral6';
  $table='parts';
  $conn = new mysqli($host, $user, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  $query="SELECT * FROM $table";

  if (isset($_GET['asc'])) {
    $query = ("SELECT * FROM $table ORDER BY `". $conn->real_escape_string($_GET['asc']) ."` ASC");
  } else if (isset($_GET['dsc'])) {
    $query = ("SELECT * FROM $table ORDER BY `". $conn->real_escape_string($_GET['dsc']) ."` DESC");
  } 

  $result = $conn->query($query);
  $rows = array();
  while($r = $result->fetch_assoc()) {
      $rows[] = $r;
  }



  print json_encode($rows);
?>