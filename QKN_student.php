<?php
    require_once('config.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
    session_start();
    if(!isset($_SESSION['userid'])){
        header('Location:login.php');
        exit;
    }
    else{
        checkExpire();
        $rl = $_SESSION['role'];
        switch ($rl){
            case 'student' :  break;
            default: header('Location:homePage.php'); exit; break;
        }
    }
    
    $kind1 = "ЯКН";
    $kind2 = "QKN";
    require('commonElectivesStudent.php');
?>