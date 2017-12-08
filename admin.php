<?php
session_start();
ob_start();
// include "dbconnectv2.php";
// // setup params

// $db = new DbConnect;






?>
<!doctype html>

<html lang="en">

<head>

    <style>
      
    </style>
    <meta charset="utf-8" />

    <title>Admin</title>
    <meta name="description" content="php viewer for parts" />
    <meta name="author" content="SitePoint" />
    <link rel="stylesheet" href="styles/main.css" />
    <link rel="stylesheet" href="styles/parts.css" />
    <link rel="stylesheet" href="styles/admin.css" />

    <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
    <script src="assets/jquery-3.2.1.min.js"></script>
    <script src="assets/virtualScroll.js"></script>

    <script>
    const cart = {};
    console.log('test');
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" id="view-parts">
                            Edit Parts
                            <span class="sr-only"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="view-shipping">
                            Edit Shipping Prices
                            <span class="sr-only"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="view-users">
                            View Registered Users
                            <span class="sr-only"></span>
                        </a>
                    </li>




                </ul>


            </nav>
        </div>
        </div>
        <div class="container">
            <div class="header">

                <div class="headerTitle">
                    <div class="headerSpacer"></div>
                    <span>Car Parts</span>
                </div>

                <div class="headerSpace"></div>

                <div class="headerMenu">
                    <?php if (isset($_SESSION['username'])) { echo "<a href=\"user.php\">welcome " . $_SESSION['username'] . "</a>"; } ?><?php if (!isset($_SESSION['username'])) { echo '<a href="login.php">Login</a>'; } ?><?php if (isset($_SESSION['username'])) { echo '<div class="headerSpacer"></div><a href="logout.php">Logout</a>'; } ?>
                    <div class="headerSpacer"></div>
                </div>
            </div>

            <div class="body">
                <!-- START BODY -->

                <div class="partsContainer">
                    
                    <div class="card partsBox" id="shipping">
                    </div>
                    <div class="card partsBox" id="users">
                    </div>

                    <div class="card partsBox" id="parts">

                        <div class="partsHeader">
                            <div class="partsSpacer"></div>

                            PARTS

                            <div class="partsSpace"></div>

                            <div class="partsSpace2"></div>

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
                            <div id="partsHolder"></div>
                            <div id="partsItems"></div>
                        </div>
                    </div>
                </div>

                

                        <!-- column chooser -->
                        <div class="colOverlay" id="colOverlay" style="display: none;">
                            <div class="colChooser">
                                <span>Column Chooser</span>

                                <div id="colMenu">
                                    <input type="radio" name="gender" value="male" id="0" />Male
                                    <br />
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
                                    Male
                                    <button>asc</button>
                                    <button>dsc</button>
                                    <br />
                                </div>

                                <button type="button" id="quit2">exit</button>
                            </div>
                        </div>
                        <!-- end sort chooser -->
                        <!-- END BODY -->
                    </div>
        </div>

        <!-- START JS SCRIPTS -->
        <script src="assets/admin.js" async></script>

        <!-- END JS SCRIPTS -->
</body>



</html>