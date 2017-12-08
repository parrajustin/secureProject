<?php
  session_start();
  ob_start();

  if (!isset($_SESSION['username'])) {
    header ('location: index.php');
  }

  // setup params
  require_once('./db.php');
  $db = new dbClass();
  // setup params
  $conn = $db->getConnection();


  if (isset($_POST['submit']) && $_POST['submit'] == 'confirm' && isset($_SESSION['checkout'])) {
      $checkout = $_SESSION['checkout'];

    //   $_SESSION['checkout'] = array(
    //     "orderInfo" => $cart,
    //     "weight" => $totalWeight,
    //     "cost" => $total,
    //     "zip" => $zip,
    //     "state" => $stateAbr
    // );

    $stmt1 = $conn->prepare("INSERT INTO orders (username, orderInfo, weight, cost, zip, state) VALUES (?, ?, ? ,?, ?, ?)");
    $stmt1->bind_param("ssiiis", $_SESSION['username'], $checkout['orderInfo'], $checkout['weight'], $checkout['cost'], $checkout['zip'], $checkout['state']);
    $stmt1->execute();
    unset($_SESSION['checkout']);
    header ('location: ./user.php');
  } else if (isset($_POST['submit']) && $_POST['submit'] == 'cancel') {
    header ('location: ./index.php');
  } else if (!isset($_POST['zip']) || !isset($_POST['hiddenCart'])) {
    header ('location: ./index.php');
  }

  $zip = json_decode($_POST['zip']);
  $cart = json_decode($_POST['hiddenCart']);

  if (count($cart) == 0) {
    header ('location: ./index.php');
  }
  $weight = 0;
  $totalWeight = 0;
  $price = 0;

?>


<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>File Viewer</title>
  <meta name="description" content="php viewer for parts">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/order.css">
    <style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
    </style>

  <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
  <script src="assets/jquery-3.2.1.min.js"></script>
  <script> 
    const cart = <?php echo json_encode($cart); ?>;
    </script>
</head>

<body>

    <div class="container">
        <div class="header">

            <div class="headerTitle">
                <div class="headerSpacer"></div>
                <a class="navLink headerText" href="./index.php">Car Site</a>
            </div>

            <div class="headerSpace"></div>

            <div class="headerMenu">
                <?php 
                if (isset($_SESSION['username'])) { 
                    echo "welcome " . $_SESSION['username'] . "<div class=\"headerSpacer\"></div><a class=\"navLink\" href=\"user.php\">User Page</a>"; 
                } if (isset($_SESSION['admin']) && ($_SESSION['admin'] == 1)) { 
                    echo "<div class=\"headerSpacer\"></div><a class=\"navLink\" href=\"admin.php\">Admin Page</a>"; 
                }
                
                if (!isset($_SESSION['username'])) { 
                    echo '<a class="navLink" href="login.php">Login</a>'; 
                } 
            
                if (isset($_SESSION['username'])) { 
                    echo '<div class="headerSpacer"></div><a class="navLink" href="logout.php">Logout</a>'; 
                } ?>
                <div class="headerSpacer"></div>
            </div>
        </div>

        <div class="body">
            <!-- START BODY -->

            <div class="orderContainer">
                <div class="card box">
                    <form action="order.php" method="post">
                        <input type="text" id="hiddenCart" name="hiddenCart" style="display: none;" placeholder="[]" value=""/>

                        <h1> Buy Order Conformation</h1>

                        <table>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Weight
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Quantity
                                </th>
                            </tr>
                            <?php
                                for($c = 0; $c < count($cart); $c = $c + 1) {
                                    $value = $cart[$c];
                                    echo "<tr><td>";
                                    echo $value->name;
                                    echo "</td><td>";
                                    echo $value->weight;
                                    echo "</td><td>";
                                    echo $value->price;
                                    echo "</td><td>";
                                    echo $value->quantity;
                                    echo "</td></tr>";
                                    $price += $value->price * $value->quantity;
                                    $weight += $value->weight * $value->quantity;
                                }
                            ?>
                        </table>

                        <br>
                        <?php 
                            echo "ZIP: $zip </br>"; 

                            $totalWeight = $weight;
                            if ($weight > 150) {
                              $weight = 150;
                            }
                          
                            $lowerZip = $zip % 1000;
                          
                            $stmt = $conn->prepare("SELECT * FROM `upscost` WHERE `weight` = ?");
                            $stmt->bind_param("i", $weight);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $Objweight = $result->fetch_assoc();
                            
                            $stmt1 = $conn->prepare("SELECT * FROM `ziptostate` WHERE `lowZip` <= ? AND `highZip` >= ?");
                            $stmt1->bind_param("ii", $zip, $zip);
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

                            echo "State: $state($stateAbr)</br>";
                            echo "Zone: $zone</br>";
                            echo "Shipping cost: $cost</br>";
                            echo "Item cost: $price</br>";
                            $total = $price + $cost;
                            if ($stateAbr == "TX") {
                                echo "Texas TAX: " . (($price + $cost) * 0.0625) .  "</br>";
                                $total = (($price + $cost) * 1.0625);
                            }
                            echo "<h1>TOTAL COST: " . $total . "</h1>";

                            $_SESSION['checkout'] = array(
                                "orderInfo" => $_POST['hiddenCart'],
                                "weight" => $totalWeight,
                                "cost" => $total,
                                "zip" => $zip,
                                "state" => $stateAbr
                            );
                        ?>

                        <button type="submit" name="submit" value="confirm">CONFIRM</button>
                        <button type="submit" name="submit" value="cancel">CANCEL</button>
                    </form>
                </div>
            </div>
            <!-- END BODY -->
        </div>
    </div>

    <!-- START JS SCRIPTS -->
    <script>
        $('#hiddenCart').val(JSON.stringify(hiddenCart));
        $('#hiddenCart').val(JSON.stringify(hiddenCart));
    </script>
    <!-- END JS SCRIPTS -->
</body>

</html>