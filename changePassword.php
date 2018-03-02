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
            case 'administrator' : include "admin_menu_change.html"; break;
            case 'student' : include "student_menu_change.html"; break; 
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
    <link rel="stylesheet" href="css/changePassword.css">
    <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
    <script type="text/javascript" src="javascript/jquery.js"></script>
    <script type="text/javascript" src="javascript/validate.js"></script>
    <title>Change Password</title>
</head>

<body>
  <h2>Смяна на парола</h2>
  <?php
  if(isset($_GET['warning'])){
    $strenc= $_GET['warning'];
    $msg = unserialize(urldecode($strenc));
    if($msg[0] =="wrongPassword"){
       echo '<h3 id="err" style="display:block;">Грешна парола!</h3>';
       }
  }
  else{
       echo '<h3 id="err" style="display:none;">placeholder</h3>';
  }
  ?>
  <div id="box">
        <form method="POST" action="change.php" onSubmit="return validateChange()" method="POST">
			<div class="group">
			<label for="passLogin">Стара парола</label><br>
			<input type="password" name="old" placeholder="стара парола..." id="old" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="passLogin">Нова парола</label><br>
			<input type="password" name="new" placeholder="нова парола..." id="new" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="passLogin">Повтори новата парола</label><br>
			<input type="password" name="repeat" placeholder="повтори новата парола..." id="repeat" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <button type="submit" id="submit" >Смени</button>
		</form>
  </div>
</body>
</html>