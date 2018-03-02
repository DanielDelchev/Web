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

<!doctype html>
<html>
<footer>
  <div id="menu">
         <ul>
          <li><a href="homePage.php">Начало</a></li>
          <li><a class='active' href="StudentElectivesMain.php">Записване/Преглед на избираеми</a></li>
          <li><a href="OKN_student.php">Записване на ОКН</a></li>
          <li><a href="QKN_student.php">Записване на ЯКН</a></li>
          <li><a href="math_student.php">Записване на Математика</a></li>
          <li><a href="logout.php">Изход</a></li>
        </ul> 
  </div>
</footer>
</html>
