<?php
/**
 * Erik Corral
 * Ajax calls
 * 05/04/2017
 * */


	require_once 'dbconnectv2.php';

    if(isset($_SESSION['db']))
    {
    $Db = new DbConnect;
    $DbObj = $_SESSION['db'] = $Db;


    }


	if ($_SERVER['REQUEST_METHOD'] == "POST"){
		if ($_POST['trigger'] === 'getCost'){
			$DbObj->getCost($_POST['price'],$_POST['zip'],$_POST['weight']);
		}

        if ($_POST['trigger'] === 'getTable'){
			$DbObj->getTable($_POST['table']);
		}




	}
?>