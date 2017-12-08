<?php
  session_start();
  ob_start();

  // setup params
  $host='earth.cs.utep.edu';
  $user='ecorral6';
  $password='YgS&yMn&';
  $database='ecorral6';
  $table='users';
  $conn = new mysqli($host, $user, $password, $database);

  $error = "";

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  if (isset($_SESSION['username'])) {
    header ('location: ./user.php');
  }

  if (isset($_POST['submit']) && $_POST['submit'] === "login" && isset($_POST['uname']) && isset($_POST['password'])) {
    $query="SELECT * FROM $table where `username` = '" . $_POST['uname'] . "'";
    // echo $query;
    $result = $conn->query($query);

    $rows = array();
    while($r = $result->fetch_assoc()) {
      $rows[] = $r;
    }

    if (count($rows) == 0) {
      $error = "No user found with username: '" . $_POST['uname'] . "'";
    } else {
      $user = $rows[0];
      
      $salt = $user['salt'];
      $passh = sha1($_POST['password'] . $salt);
      if ($passh == $user['password']) {
        $_SESSION['username'] = $_POST['uname'];
        $_SESSION['address'] = $user['address'];
        header ('location: ./user.php');
      } else {
        $error = "Incorrect password";
      }
    }
  } else if (isset($_POST['submit']) && $_POST['submit'] === "register" && isset($_POST['cpassword']) && isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['addr'])) {
    if (strlen($_POST['uname']) < 6) {
      $error = $error . "username must be at least 6 characters. ";
    }

    if ($_POST['password'] != $_POST['cpassword']) {
      $error = $error . "password and confim password aren't the same. ";
    }

    if (strlen($_POST['password']) < 6) {
      $error = $error . "password must be at least 6 characters. ";
    }
    
    if (strlen($_POST['addr']) < 1) {
      $error = $error . "must have an address. ";
    }
    
    if (strlen($error) == 0) {
      $uname=($_POST['uname']);
      $password=($_POST['password']);
      $address=($_POST['addr']);

      $salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
      $passh = sha1($password . $salt);

      $stmt = $conn->prepare("INSERT INTO users (username, password, address, salt) VALUES (?, ?, ? ,?)");
      $stmt->bind_param("ssss", $uname, $passh, $address, $salt);
      $stmt->execute();

      
      $_SESSION['username'] = $_POST['uname'];
      $_SESSION['address'] = $_POST['addr'];
      header ('location: ./user.php');
    }
  } else if(isset($_POST['submit'])) {
    $error = "NOT ALL FIELDS WERE SET";
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
  <link rel="stylesheet" href="styles/login.css">
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

    <div class="fullbody">
      <!-- START BODY -->

      <div class="login">
        <div class="loginHeader">
          <div class="headerSpacer"></div>
          <span>Login/Register</span>
          <div class="headerSpacer"></div>
        </div>

        <div class="loginBody">
          <form name="login" action="login.php" method="post" onsubmit="return validateFormLogin()">
          username: <input type="text" name="uname" required><br><br>
          password: <input name="password" type="password" required><br><br>
          <button style="width: 100%;" type="submit" name="submit" value="login"> login </button>
          </form>
        </div>

        <div class="loginBody">
          <form name="register" action="login.php" method="post" onsubmit="return validateFormRegister()">
          username: <input type="text" name="uname" required><br><br>
          password: <input name="password" type="password" required><br><br>
          confirm pass: <input name="cpassword" type="password" required><br><br>
          address: <input name="addr" type="text" required><br><br>
          <button style="width: 100%;" type="submit" name="submit" value="register"> Register </button>
          </form>
        </div>
      </div>

      <!-- END BODY -->
    </div>
  </div>

  <!-- START JS SCRIPTS -->
  <script src="assets/login.js" async></script>
  <!-- END JS SCRIPTS -->
</body>

</html>