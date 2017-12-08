<?php
    ob_start();
    session_start();

    
    // setup params
    require_once('./db.php');
    $db = new dbClass();
  
    // setup params
    $conn = $db->getConnection();
    
    $table='users';
    $query="SELECT * FROM $table where `username` = '" . $_SESSION['username'] . "'";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();
    $error = "";
    
    // var_dump($user);


    if (!isset($_SESSION['username'])) { 
        header ('location: ./index.php'); 
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'edit') {
        $updatingPass = strlen($_POST['password']) != 0;
        $updatingAddr = strlen($_POST['addr']) != 0;

        if ($updatingPass && $_POST['password'] != $_POST['cpassword']) {
            $error = $error . "password and confim password aren't the same. ";
        }
    
        if ($updatingPass && strlen($_POST['password']) < 6) {
            $error = $error . "password must be at least 6 characters. ";
        }
        
        if ($updatingAddr && strlen($_POST['addr']) < 1) {
            $error = $error . "must have an address. ";
        }
        
        if (strlen($error) == 0 && $updatingAddr && $updatingPass) {
            $password=($_POST['password']);
            $address=($_POST['addr']);
        
            $salt = $user['salt'];
            $passh = sha1($password . $salt);
        
            $stmt = $conn->prepare("UPDATE users SET password = ?, address = ? WHERE username = ?");
            $stmt->bind_param("sss", $passh, $address, $_SESSION['username']);
            $stmt->execute();
        
            $_SESSION['address'] = $_POST['addr'];
            $error = "Success, updated both password and address!";
        } else if (strlen($error) == 0 && $updatingAddr) {
            $address=($_POST['addr']);
        
            $stmt = $conn->prepare("UPDATE users SET address = ? WHERE username = ?");
            $stmt->bind_param("ss", $address, $_SESSION['username']);
            $stmt->execute();
            
            $_SESSION['address'] = $_POST['addr'];
            $error = "Success, updated address!";
        } else if (strlen($error) == 0 && $updatingPass) {
            $password=($_POST['password']);
        
            $salt = $user['salt'];
            $passh = sha1($password . $salt);
        
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $passh, $_SESSION['username']);
            $stmt->execute();
        
            $error = "Success, updated password!";
        }
    }
?>

<!doctype html>

<html lang="en">

<head>
<meta charset="utf-8">

<title>File Viewer</title>
<meta name="description" content="php viewer for parts">
<meta name="author" content="SitePoint">
<link rel="stylesheet" href="styles/main.css">
<link rel="stylesheet" href="styles/user.css">
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

<script>
<?php if ($error != '') echo "alert(\"$error\");"; ?>
</script>

<!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
<![endif]-->
<script src="assets/jquery-3.2.1.min.js"></script>
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

    <div class="userContainer">
        <div class="card box">
            <h1>Welcome: <?php echo $_SESSION['username']; ?></h1>

            you live at <?php echo $_SESSION['address']; ?>

            <h2> Edit information </h2>

            <div>
                <form name="edit" action="user.php" method="post" onsubmit="return validateFormEdit()">
                password: <input name="password" type="password" ><br><br>
                confirm pass: <input name="cpassword" type="password" ><br><br>
                address: <input name="addr" type="text" placeholder="<?php echo $_SESSION['address'] ?>"><br><br>
                <button style="width: 100%;" type="submit" name="submit" value="edit"> Edit </button>
                </form>
            </div>

            <h2> Past orders </h2>

            <table>
                <tr>
                    <th>
                        Order id
                    </th>
                    <th>
                        Price
                    </th>
                    <th>
                        Weight
                    </th>
                    <th>
                        Zip
                    </th>
                    <th>
                        State
                    </th>
                </tr>
            <?php
                $stmt = $conn->prepare("SELECT * FROM orders WHERE `username` = ?");
                $stmt->bind_param("i", $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = array();
                while($r = $result->fetch_assoc()) {
                    // var_dump($r);
                    echo "<tr><td>";
                    echo $r['orderID'];
                    echo "</td><td>";
                    echo $r['cost'];
                    echo "</td><td>";
                    echo $r['weight'];
                    echo "</td><td>";
                    echo $r['zip'];
                    echo "</td><td>";
                    echo $r['state'];
                    echo "</td></tr>";
                }
            ?>
            </table>
        </div>
    </div>

    <!-- END BODY -->
  </div>
</div>

<!-- START JS SCRIPTS -->
  <script src="assets/edit.js" async></script>
<!-- END JS SCRIPTS -->
</body>

</html>