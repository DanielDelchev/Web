<?php
    $DEBUG=1;
    $DEPLOY=0;
    ini_set('display_errors',$DEPLOY);
        
    
	$user = "root";
	$host = "localhost";
	$pass = "";
	$db   = "projectDB";
	
	try {
		$connection = new PDO("mysql:host=$host;dbname=$db;", $user, $pass);
	} catch(PDOException $e) {
		echo "Error: ".$e->getMessage();
		exit;
	}
	$connection->query("SET NAMES utf8");

    
    
    $IP = 'http://localhost';
    $emailPort='5555';
    $rootDir='project81211';

function checkExpire(){
    $expireTime = 3600/2;
    if(($_SESSION['expire']-mktime() <0) ){
        echo $_SESSION['expire'];
        echo '<br>';
        echo mktime();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
    else{
        $_SESSION['expire'] = mktime() + $expireTime;
    }
}


function checkCampaign(){

    global $connection;
    //$connection->beginTransaction();
    $currentDate = Date("Y-m-d");
    $query = "SELECT * FROM campaigns WHERE '".$currentDate."' >= start AND '".$currentDate."' <= finish ;";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $found = $stmt->fetch(PDO::FETCH_ASSOC);
    //$connection->commit();
 
    if ($found!=null){
        return true;
    }
    
    return false;
}
    
?>
