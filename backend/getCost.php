<?php

if (!isset($_GET['cost']) || !isset($_GET['zip']) || !isset($_GET['weight'])) {
  echo "[0]";
} else {
  $weight = $_GET['weight'];
  if ($_GET['weight'] > 150) {
    $weight = 150;
  }

  $lowerZip = $_GET['zip'] % 1000;

  $host='localhost';
  $user='root';
  $password='';
  $database='project';
  $table='users';
  $conn = new mysqli($host, $user, $password, $database);

  $stmt = $conn->prepare("SELECT * FROM `upscost` WHERE `weight` = ?");
  $stmt->bind_param("i", $weight);
  $stmt->execute();
  $result = $stmt->get_result();
  $Objweight = $result->fetch_assoc();
  
  $stmt1 = $conn->prepare("SELECT * FROM `ziptostate` WHERE `lowZip` <= ? AND `highZip` >= ?");
  $stmt1->bind_param("ii", $_GET['zip'], $_GET['zip']);
  $stmt1->execute();
  $result = $stmt1->get_result();
  $Stateweight = $result->fetch_assoc();
  
  $stmt2 = $conn->prepare("SELECT * FROM `ziptozone` WHERE (`lowZip` <= ? AND `highZip` >= ?) OR (`lowZip` = ?)");
  $stmt2->bind_param("iii", $lowerZip, $lowerZip, $lowerZip);
  $stmt2->execute();
  $result = $stmt2->get_result();
  $Zoneweight = $result->fetch_assoc();

  $zone = $Zoneweight['zoneGround'];
  $state = $Stateweight['StateName'];
  $stateAbr = $Stateweight['StateAbrv'];
  $cost = $Objweight['zone' . $zone];

  $returnee = array(
    "Zone" => $zone,
    "State" => $state,
    "StateAbr" => $stateAbr,
    "ShippingCost" => $cost,
    "TotalCost" => $_GET['cost'] + $cost
  );

  echo json_encode($returnee);
}

?>