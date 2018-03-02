<?php
    require_once('config.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
    if(!isset($_SESSION['userid'])){
        header('Location:login.php');
        exit;
    }
    else if ($_SESSION['role']!='administrator'){
        header('Location:homePage.php');
        exit;
    }
    checkExpire();
?>

<html>
<footer>
  <div id="menu">
         <ul>
          <li><a class='active' href="homePage.php">Начало</a></li>
          <li><a href="changePassword.php">Смяна на парола</a></li>
          <li><a href="changeRole.php">Промени роля</a></li>
          <li><a href="campaigns.php">Кампании за изборни</a></li>
          <li><a href="electives.php">Изборни дисциплини</a></li>
          <li><a href="logout.php">Изход</a></li>
        </ul> 
  </div>
</footer>
</html>