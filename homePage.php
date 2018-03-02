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
            case 'administrator' : include "admin_menu_homePage.php"; break;
            case 'student' : include "student_menu_homePage.php"; break;
        }
    }
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/homePage.css">
    <title>Home</title>
</head>
    
<body>

<?php

    $name = $_SESSION['userid'];
    $role = $_SESSION['role'];
    $res = '';
    switch($role){
        case 'administrator': $res = 'администратор'; break;
        case 'student': $res = 'студент'; break;
    }
    echo "<h2 class = 'greeting' >Здравейте, ".$name." ! Влезли сте в системата като ".$res.".</h2>"
?>

</body>
</html>
