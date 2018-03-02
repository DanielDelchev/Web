<?php
require_once('config.php');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
session_start();
if(isset($_SESSION['userid'])){
    header('Location:homePage.php');
    exit;
}

include "shortmenu_warning.html";

?>

<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/shortmenu.css">
    <link rel="stylesheet" href="css/warning.css">
    
    <title>Warning/Notification</title>
</head>
    
<body>

<?php
if (isset($_GET['warning'])){
    $warning = $_GET['warning'];

    $strenc= $_GET['warning'];
    $arr = unserialize(urldecode($strenc));
    
    $msg = "";
    foreach ($arr as $key){
        switch ($key){
            case 'takenEmail':
            $msg = $msg.' <h2 class = "warning">Електронната поща на профила , който се опитвате да активирате вече е заета. Направете нова регистрация!</h2><br>';
            break;
            
            case 'takenUsername':
            $msg = $msg.' <h2 class = "warning">Потребителското име на профила , който се опитвате да активирате вече е заето! Направете нова регистрация!</h2><br>';
            break;

            case 'invalidCode':
            $msg = $msg.' <h2 class = "warning">Кодът за активация,който използвате не е валиден!</h2><br>';
            break;
        }
    }
    echo $msg;
}

else if (isset($_GET['notification'])){
    $notification = $_GET['notification'];

    $strenc= $_GET['notification'];
    $arr = unserialize(urldecode($strenc));
    
    $msg = "";
    foreach ($arr as $key){
        switch ($key){
            case 'successfulValidation':
            $msg = $msg.' <h2 class = "notification">Профилът Ви е активиран.Може да влезете в системата с него.</h2><br>';
            break;
            
            case 'pleaseValidate':
            $msg = $msg.' <h2 class = "notification">Първи етап на регистрацията завърши успешно.За да завършите регистрацията, влезте в електронната поща, с която сте се регистрирали за
        да потвърдите.</h2><br>';
            break;

            case 'changeSuccessful':
            $msg = $msg.' <h2 class = "notification">Паролата Ви е сменена успешно, влезте наново в сайта.</h2><br>';
            break;
        }
    }
    echo $msg;
}

?>


</body>
</html>
