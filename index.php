<?php
  ob_start();
  session_start();
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>File Viewer</title>
  <meta name="description" content="php viewer for parts">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/parts.css">

  <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
  <script src="assets/jquery-3.2.1.min.js"></script>
  <script src="assets/virtualScroll.js"></script>

  <script>
    const cart = <?php if(!isset($_SESSION['cart']))  { echo "{}"; } else { echo json_decode($_SESSION['cart']); } ?>;
    const username = <?php if(isset($_SESSION['username'])) { echo "\"". $_SESSION['username'] . "\""; } else { echo "\"\""; } ?>;
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

            <div class="partsContainer">
                <div class="card partsBox">

                    <div class="partsHeader">
                        <div class="partsSpacer"></div>

                        PARTS

                        <div class="partsSpace"></div>

                        <div class="partsSpace2"></div>

                        <button id="checkout">Checkout: items 0</button>

                        <div class="partsSpacer"></div>

                        <button id="sortColumns">Sort Columns</button>

                        <div class="partsSpacer"></div>

                        <button id="partColumns">Edit Columns</button>

                        <div class="partsSpacer"></div>
                    </div>

                    <div class="partsColumnHeaders">
                        <div class="partsSpacer"></div>

                        <div class="partsHeaderCols" id="partsHeaderCols">
                            <div class="divider"></div>
                            <div class="col">
                                A
                            </div>
                        </div>

                        <div class="partsSpacer"></div>
                    </div>

                    <div id="partsViewer">
                        <div id="partsHolder">
                        </div>
                        <div id="partsItems">
                        </div>
                    </div>
                </div>
            </div>

            <!-- column chooser -->
            <div class="colOverlay" id="colOverlay" style="display: none;">
                <div class="colChooser">
                    <span>Column Chooser</span>

                    <div id="colMenu">
                        <input type="radio" name="gender" value="male" id="0"> Male<br>
                    </div>

                    <button type="button" id="quit">exit</button>
                </div>
            </div>
            <!-- end column chooser -->
            <!-- sort chooser -->
            <div class="colOverlay" id="colOverlay2" style="display: none;">
                <div class="colChooser">
                    <span>Sort by</span>

                    <div id="colMenu2">
                        Male <button>asc</button> <button>dsc</button><br>
                    </div>

                    <button type="button" id="quit2">exit</button>
                </div>
            </div>
            <!-- end sort chooser -->
            <!-- checkout -->
            <div class="colOverlay" id="Overlay" style="display: none;">
                <div class="colChooser">
                    <span>Checkout <?php if (!isset($_SESSION['username'])) { echo 'MUST BE LOGGED IN TO CHECKOUT!'; } ?></span>

                    <!-- </br> -->
                    <form action="backend/order.php" method="post">
                        <div id="colMenu3">
                            <table>
                                <tr>
                                    <th>
                                        Part name
                                    </th>
                                    <th>
                                        Cost
                                    </th>
                                    <th>
                                        Weight
                                    </th>
                                </tr>
                            </table>
                        </div>
                        </br>

                        Zip: <input required id="zip" name="zip" type="number" placeholder="79900" value="79900" /><br>
                        <button type="button" id="estimate">Get Cost</button> <br> <div id="cost">cost: </div><br>
                        <div id="state"></div><br>

                        <button type="button" id="quit3">exit</button>
                        <button type="submit" value="order" id="accept" style="<?php if (!isset($_SESSION['username'])) {  echo "display: none;"; }?>">BUY</button>
                    </form>
                </div>
            </div>
            <!-- end checkout -->
            <!-- END BODY -->
        </div>
    </div>

    <!-- START JS SCRIPTS -->
    <script src="assets/parts.js" async></script>
    <!-- END JS SCRIPTS -->
</body>

</html>