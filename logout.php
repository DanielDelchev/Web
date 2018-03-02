<?php
require_once('config.php');
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
if(!isset($_SESSION['userid'])){
    header('Location: login.php');
    exit;
    }
    checkExpire();
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
?>