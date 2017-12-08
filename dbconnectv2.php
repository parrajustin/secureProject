<?php
/**
 * Erik Corral
 * DbConnectv2.php
 * 12/07/2017
 * */
define ('SALT', 'wCv6VxQaoT');
if(session_id() != '' ||  !isset($_SESSION['db']))
{
    session_start();
    $_SESSION['db'] = new DbConnect;


}


if(isset($_GET['signin']))
{

    $_SESSION['db']->checkCredentials($_POST['userName'], $_POST['password']);
}

else if(isset($_GET['new']))
{

    $_SESSION['db']->createUser($_POST['userName'], $_POST['password'], $_POST['firstName'], $_POST['lastName'], $_POST['radio']);

}






class DbConnect
{


    private $host, $user, $table, $password, $database, $connection;

    /**
     * Summary of __construct
     * Constructor for class DbConnect
     */
    function __construct()
    {
        $this->host = 'earth.cs.utep.edu';
        $this->user = '';
        $this->password = '';
        $this->database = '';
        $this->connection = $this->connectSQL();
        $this->table = $this->getTableNames();
    }


    /**
     * Summary of connectSQL
     * Function connects to given sql db per construct
     * @return resource
     */

    function connectSQL()
    {

        $connection = mysqli_connect($this->host,$this->user,$this->password, $this->database);

        if(!$connection)
        {
            die('Could not connect'.mysqli_error());
        }


        /*
        $db_selected = $this->connection->select_db($this->database);

        if (!$db_selected)
        {
            die ('Invalid database!' .mysqli_error());
        }
        */

        return $connection;
    }

    /**
     * Summary of getTableNames
     * Function returns name of table 1 in db
     * @return string
     */


    function getTableNames()
    {

        $query = "SHOW TABLES FROM {$this->database}";
        $result = $this->connection->query($query);

        while ($rows = mysqli_fetch_row($result)) {
            $tables[] = $rows[0];
        }

        return $tables[0];
    }

    function getCost($cost, $zip, $weight)
    {
        if (!isset($cost) || !isset($zip) || !isset($weight)) {
            echo "[0]";
        } else {

            if ($weight > 150) {
                $weight = 150;
            }

            $lowerZip = $zip % 1000;



            $stmt = $this->connection->prepare("SELECT * FROM `upscost` WHERE `weight` = ?");
            $stmt->bind_param("i", $weight);
            $stmt->execute();
            $result = $stmt->get_result();
            $Objweight = $result->fetch_assoc();

            $stmt1 =  $this->connection->prepare("SELECT * FROM `ziptostate` WHERE `lowZip` <= ? AND `highZip` >= ?");
            $stmt1->bind_param("ii", $zip, $zip);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $Stateweight = $result->fetch_assoc();

            $stmt2 = $this->connection->prepare("SELECT * FROM `ziptozone` WHERE (`lowZip` <= ? AND `highZip` >= ?) OR (`lowZip` = ?)");
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
    }

    function getTable($table)
    {

        $query = "SELECT * FROM {$table}";
        $result = $this->connection->query($query) or die(mysqli_error());

        $y=mysqli_num_fields($result);

        $fieldnm=$result->fetch_field_direct(0);


        echo "<table class=\"table table-hover\" border='5' align='center' >";
        echo "<tr>";
        for($i=0; $i<$y; $i++){
            $fieldnm=$result->fetch_field_direct($i);
            echo "<th>".$fieldnm->name."</th>";
        }

        echo "</tr>";

        while($rowdata=mysqli_fetch_array($result)){


            echo "<tr>";

            for($j = 0; $j < $y; $j++)
            {
                echo "<td>".$rowdata[$j]."</td>";
            }
            echo "</tr>";
        }


        echo "</table>";
    }


    /**
     * Summary of viewUsers
     * prints a table with all the users in the DB
     */
    function viewTable($table)
    {




        $query = "SELECT * FROM {$table}";
        $result = $this->connection->query($query) or die(mysqli_error());

        $y=mysqli_num_fields($result);

        $fieldnm=$result->fetch_field_direct(0);


        echo "<table class=\"table table-hover\" border='5' align='center' >";
        echo "<tr>";
        for($i=0; $i<$y; $i++){
            $fieldnm=$result->fetch_field_direct($i);
            echo "<th>".$fieldnm->name."</th>";
        }

        echo "</tr>";

        while($rowdata=mysqli_fetch_array($result)){


            echo "<tr>";

            for($j = 0; $j < $y; $j++)
            {
                echo "<td>".$rowdata[$j]."</td>";
            }
            echo "</tr>";
        }


        echo "</table>";

    }

    /**
     * Summary of viewUserInfo
     * @var $user
     * Prints user info given a user name
     */

    function viewUserInfo($user)
    {




        $query = "SELECT UserName, FirstName, LastName, LastLogin FROM {$this->table} WHERE UserName = '{$user}';";
        $result = $this->connection->query($query) or die(mysql_error());

        $y=mysqli_num_fields($result);

        $fieldnm=$result->fetch_field_direct(0);


        echo "<table class=\"table table-hover\" border='5' align='center' >";
        echo "<tr>";
        for($i=0; $i<$y; $i++){
            $fieldnm=$result->fetch_field_direct($i);
            echo "<th>".$fieldnm->name."</th>";
        }

        echo "</tr>";

        while($rowdata=mysqli_fetch_array($result)){


            echo "<tr>";

            for($j = 0; $j < $y; $j++)
            {
                echo "<td>".$rowdata[$j]."</td>";
            }
            echo "</tr>";
        }


        echo "</table>";

    }

    /**
     * Summary of checkExistingUserName
     * @var $user
     * Checks to see if the username is already taken
     * returns bool
     */

    function checkExistingUserName($user)
    {
        $tables = array();
        $query = "SELECT * FROM {$this->table} WHERE UserName = '{$user}';";


        $result = $this->connection->query($query) ;

        $row = $this->result->fetch_row($result);


        if($row != false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /**
     * Summary of checkCredentials
     * @var $user, $pass
     * Checks to see if user and pass are a match on DB
     */

    function checkCredentials($user, $pass)
    {


        $tables = array();
        $pass = $this->saltPass($user, $pass);
        $query = "SELECT * FROM {$this->table} WHERE UserName = '{$user}' AND Password = '{$pass}'";


        $result = $this->connection->query($query) ;

        $row = $result->fetch_row($result);


        if($row != false) {
            if($row[4] == 0)
            {
                $_SESSION['login'] = $_SESSION['user'] = $user;
                $query = "UPDATE {$this->table} SET LastLogin = '{$this->getDate()}' WHERE UserName = '{$user}'; ";
                $this->connection->query($query) or die(mysqli_error());;


                header("Location: mainpage.php" );
            }

            else if($row[4] == 1)
            {

                $query = "UPDATE {$this->table} SET LastLogin = '{$this->getDate()}' WHERE UserName = '{$user}'; ";
                $result = $this->connection->query($query) or die(mysqli_error());

                $_SESSION['login'] = $_SESSION['admin'] = $user;
              header("Location: mainpage.php" );
            }
        }

        else
        {

            header("Location: signin.php?fail" );
        }

    }

    /**
     * Summary of getDate
     * returns a DateTime String
     */

    function getDate()
    {
        $date = new DateTime("now");
        $date = $date->format("Y-m-d H:i:s");
        return $date;
    }

    /**
     * Summary of createUser
     * @var $user, $pass, $firstName, $lastName, $type
     * Creates a user
     * returns header
     */

    function createUser($user, $pass, $firstName, $lastName, $type)
    {


        if($this->checkExistingUserName($user))
        {

            return header('Location: admin.php?exists');
        }

        else
        {



        $date = new DateTime("now");

        $query =  "INSERT INTO {$this->table} (UserName, Password, FirstName, LastName, Type, CreatedOn, LastLogin) VALUES ('{$user}' , '{$this->saltPass($user,$pass)}', '{$firstName}', '{$lastName}', {$type}, '{$this->getDate()}', NULL);  ";
        $result = $this->connection->query($query, $this->connection);
        if($result = 1)
        {

           return  header("Location: admin.php?success=1&user={$user}");
        }
        else
        {

          return  header('Location: admin.php?fail');
        }
        }
    }

    /**
     * Summary of saltPass
     * @var $user, $pass
     * Adds some spice to the password
     * returns String
     */

    function saltPass($user, $pass)
    {
        return SALT.$pass.$user;
    }





}






?>
