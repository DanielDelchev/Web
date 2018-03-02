<?php
require_once('config.php');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
session_start();
if(isset($_SESSION['userid'])){
    header('Location:homePage.php');
    exit;
}
else{
    include "shortmenu_register.html";
}

?>

<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/shortmenu.css">
    <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
    <script type="text/javascript" src="javascript/jquery.js"></script>
    <script type="text/javascript" src="javascript/validate.js"></script>
    
    <title>Register</title>
</head>
    
<body>
  <h2>Регистрация</h2>
  <?php
  if(isset($_GET['warning'])){
    $strenc= $_GET['warning'];
    $arr = unserialize(urldecode($strenc));
    $msg = "";
    foreach ($arr as $key => $value){
        switch ($key){
        case 'takenUsername':
            $msg = $msg."Потребителкото име ".$value." вече е заето!<br>"; break;
        case 'takenEmail':
            $msg = $msg."Пощата ".$value." вече е заета!<br>\n";break;
        }
    }
    echo '<h3 id="err" style="display:block;">'.$msg.'</h3>';
  }
  else{
       echo '<h3 id="err" style="display:none;">placeholder</h3>';
  }
  ?>
  <div id="box">
        <form method="POST" action="validateRegistration.php" onSubmit="return validate()" method="POST">
			<div class="group">
			<label for="username">Потребителско име</label><br>
            <?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['prevUser'];
                if ($val!=""){
                    echo '<input type="text" name="username" placeholder="потребителско име..." id="username" value='.$val.' required autocomplete="off"/>';
                }
                }
            else{
                echo '<input type="text" name="username" placeholder="потребителско име..." id="username" required autocomplete="off"/>';
            }  
            ?>
            <span class="highlight"></span>
            <span class="bar"></span>
			</div>
        	<div class="group">
			<label for="passLogin">SUSI поща</label><br>
            <?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['prevMail'];
                if ($val!=""){
                    echo '<input type="text" name="email" placeholder="susi поща..." id="email" value='.$val.' required autocomplete="off"/>';
                }
                }
            else{
                echo '<input type="text" name="email" placeholder="susi поща..." id="email" required autocomplete="off"/>';
            }  
            ?>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
			<div class="group">
			<label for="passLogin">Парола</label><br>
			<input type="password" name="password" placeholder="парола..." id="password" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="passLogin">Повтори парола</label><br>
			<input type="password" name="repeat" placeholder="повтори парола..." id="repeat" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <button type="submit" id="submit" >Регистрирай</button>
		</form>
  </div>
</body>
</html>