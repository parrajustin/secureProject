<?php
class dbClass {
  static $connection;
  static $check = 0;
  
  function __construct()
  {

      // $host='earth.cs.utep.edu';
      // $user='ecorral6';
      // $password='YgS&yMn&';
      // $database='ecorral6';

      $host = 'localhost:3306';
      $user = 'root';
      $password = '';
      $database = 'project';

      if (self::$check == 0) {
        self::$check = 1;
        self::$connection = mysqli_connect($host,$user,$password, $database);
        if(!self::$connection)
        {
            die('Could not connect'.mysqli_error());
        }
      }

  }

  public function getConnection() {
    return self::$connection;
  }
}
?>