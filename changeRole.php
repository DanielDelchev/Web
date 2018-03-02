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
            case 'administrator' : include "admin_menu_changeRole.php"; break;
            default: header('Location:homePage.php'); exit; break;
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
    <title>Change Role</title>
</head>

<body>
  <h2>Промяна на права</h2>
  <?php
  if(isset($_GET['warning'])){
    $strenc= $_GET['warning'];
    $msg = unserialize(urldecode($strenc));
    if($msg[0] =="noSuchUserEmail"){
       echo '<h3 id="err" style="display:block;">Потребител с такъв email не съществува!</h3>';
       }
    else if($msg[0] =="self"){
       echo '<h3 id="err" style="display:block;">Не може да променяте собствената си роля!</h3>';
       }
  }
  
  else if(isset($_GET['notification'])){
    $strenc= $_GET['notification'];
    $msg = unserialize(urldecode($strenc));
    if($msg[0] =="successfulRoleChange"){
       echo '<h3 id="not" style="display:block;">Ролята бе успешно променена!</h3>';
       }
  }
  else{
       echo '<h3 id="err" style="display:none;">placeholder</h3>';
  }

  ?>
  <div id="box">
        <form method="POST" action="grantRole.php" onSubmit="return validateEmail()" method="POST">
			<div class="group">
			<label for="passLogin">Суси поща на потребител</label><br>
			<input type="text" name="email" placeholder="суси поща..." id="email" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="passLogin">Роля</label><br>
            <select name="role" id="role" required>
              <option value="administrator">Администратор</option>
              <option value="student">Студент</option>
            </select> 
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <button type="submit" id="submit" >Смени</button>
		</form>
  </div>
</body>
</html>