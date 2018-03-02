<?php
    require_once('config.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
    if(!isset($_SESSION['userid'])){
        header('Location:login.php');
        exit;
    }
    else if (!isset($_SESSION['userid']) || $_SESSION['role']!='student'){
        header('Location:homePage.php');
        exit;
    }
    checkExpire();
?>


<html>
<footer>
  <div id="menu">
         <ul>
          <li><a href="homePage.php">Начало</a></li>
          <li><a href="changePassword.php">Смяна на парола</a></li>
          <li><a class='active' href="myElectives.php">Мойте избираеми</a></li>
          <li><a href="studentElectivesMain.php">Записване/Преглед на избираеми</a></li>                    
          <li><a href="logout.php">Изход</a></li>
        </ul> 
  </div>
</footer>
</html>