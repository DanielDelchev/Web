<?php
require_once('config.php');
require_once('User.php');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
session_start();

if(!isset($_SESSION['userid'])){
    if(isset($_POST['username'], $_POST['password'])) {
        $user = new User($_POST['username'], $_POST['password'], $connection);
        if($user->isValid()){
            $_SESSION['userid'] = $_POST['username'];
            
            $connection->beginTransaction();
			$query = "SELECT role FROM users WHERE username = '".$_POST['username']."';";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection->commit();

            $_SESSION['expire'] = mktime() + $expireTime;
            
            $_SESSION['role'] = $result['role'];
            header('Location: homePage.php');
            exit;
        }
        else{
            $username = $_POST['username'];
            $val = array('name'=>$username);
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: login.php?invalidCredentials='.$strenc);
            exit;
        }
    }
}
header('Location: login.php');
exit;
?>
