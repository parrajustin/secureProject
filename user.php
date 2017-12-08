<?php
    ob_start();
    session_start();

    
    // setup params
    $host='earth.cs.utep.edu';
    $user='ecorral6';
    $password='YgS&yMn&';
    $database='ecorral6';
    $table='users';
    $conn = new mysqli($host, $user, $password, $database);
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
      <span>Car Parts</span>
    </div>

    <div class="headerSpace"></div>
    
    <div class="headerMenu">
      <?php if (isset($_SESSION['username'])) { echo "welcome " . $_SESSION['username']; } ?>
      
      
        <?php if (isset($_SESSION['username'])) { echo '<div class="headerSpacer"></div><a href="logout.php">Logout</a>'; } if (isset($_SESSION['admin']) && ($_SESSION['admin'] == 1)) { echo "<a href=\"admin.php\">Admin</a>"; } ?>
      <div class="headerSpacer"></div>
    </div>
  </div>

  <div class="body">
    <!-- START BODY -->

    <div class="userContainer">
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
    </div>

    <!-- END BODY -->
  </div>
</div>

<!-- START JS SCRIPTS -->
  <script src="assets/edit.js" async></script>
<!-- END JS SCRIPTS -->
</body>

</html>