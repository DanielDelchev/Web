<?php
require_once('config.php');
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
if(isset($_SESSION['userid'])){
    header('Location:homePage.php');
    exit;
}
else{
    include "shortmenu_login.html";
}
?>

<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/shortmenu.css">
    <title>Log in</title>
</head>
    
<body>
  <h2>Вход</h2>
    <?php
    if(isset($_GET['invalidCredentials'])){
        echo '<h3 id="err"> Грешно потребителско име или парола</h3>';
    }
    ?>
  <div id="box">
        <form method="POST" action="validate.php">
			<div class="group">
			<label for="username">Потребителско име</label><br>
            <?php
                if(isset($_GET['invalidCredentials']) && $_GET['invalidCredentials']!=""){
                $strenc= $_GET['invalidCredentials'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['name'];
                    echo '<input type="text" name="username" placeholder="потребителско име..." id="username" pattern="[1-9a-zA-Z]*" value='.$val.' required autocomplete="off"/>';
                }
                else {
                    echo '<input type="text" name="username" placeholder="потребителско име..." id="username" pattern="[1-9a-zA-Z]*" required autocomplete="off"/>';
                }
               
            ?>
            <span class="highlight"></span>
            <span class="bar"></span>
			</div>
			<div class="group">
			<label for="passLogin">Парола</label><br>
			<input type="password" name="password" placeholder="парола..." id="password" pattern="[1-9a-zA-Z]*" required autocomplete="off"/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
			<input type="submit" class='button' value="Вход">
		</form>
  </div>
</body>
</html>