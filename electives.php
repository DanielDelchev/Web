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
            case 'administrator' :  break;
            default: header('Location:homePage.php'); exit; break;
        }
    }
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="lang" content="bg">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/homePage.css">
    <link rel="stylesheet" href="css/campaign.css">
    <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
    <script type="text/javascript" src="javascript/jquery.js"></script>
    <script type="text/javascript" src="javascript/validate.js"></script>
    <title>Add electives</title>
</head>


<?php

        if (isset($_POST['electiveName'])&&isset($_POST['lecturerName'])&&isset($_POST['credits'])&&isset($_POST['type'])&&isset($_POST['max'])){
     
        $lecturer = $_POST['lecturerName'];     
        $subject = $_POST['electiveName'];
        $type = $_POST['type'];
        $credits = $_POST['credits'];
        $max = $_POST['max'];
  
        $connection->beginTransaction();
        $query = "SELECT * FROM electives WHERE electiveName = '".$subject."';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $found = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
             
        if ($found!=null){
            var_dump($subject);
            $val = array('takenName'=>$subject,'lecturer'=>$lecturer,'type'=>$type,'credits'=>$credits,'max'=>$max);
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: electives.php?warning='.$strenc);
            exit;
        }
        
        else {
            $connection->beginTransaction();
            $query = "INSERT INTO `electives` (lecturerName, electiveName, type, credits,maxSlots) VALUES ('".$lecturer."','".$subject."','".$type."','".$credits."','".$max."');";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            require_once('mailer.php');
            notify($subject,$lecturer,$type,$credits,$max,true);
            header('Location: electives.php');
            exit;
            }
        }
?>


<body>
  <h2>Добавяне на избираема дисциплина</h2>
  <?php
  if(isset($_GET['warning'])){
    $strenc= $_GET['warning'];
    $arr = unserialize(urldecode($strenc));
    $msg = "";
    foreach ($arr as $key => $value){
        switch ($key){
        case 'takenName':
            $msg = $msg."Изборна с това име (".$value.") вече съществува!<br>"; break;
        default: break;
        }
    }
    echo '<h3 id="err" style="display:block;">'.$msg.'</h3>';
  }
  else{
       echo '<h3 id="err" style="display:none;">placeholder</h3>';
  }
  ?>
  <div id="box">
        <form class="form1" method="POST" action="electives.php" onSubmit="return validateElective()">
			<div class="group">
			<label for="electiveName">Име на изборна</label><br>
            <?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['takenName'];
                if ($val!=""){
                    echo '<input type="text" name="electiveName" placeholder="изборна дисциплина" id="electiveName" value="'.$val.'" required autocomplete="off"/>';
                }
                }
            else{
                echo '<input type="text" name="electiveName" placeholder="изборна дисциплина" id="electiveName" required autocomplete="off"/>';
            }  
            ?>            
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
			<div class="group">
			<label for="lecturerName">Име на преподавател</label><br>
			<?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['lecturer'];
                if ($val!=""){
                    echo '<input type="text" name="lecturerName" id="lecturerName" placeholder="име на преподавател" value="'.$val.'" required autocomplete="off"/>';
                }
                }
            else{
                echo '<input type="text" name="lecturerName" id="lecturerName" placeholder="име на преподавател" required autocomplete="off"/>';
            }  
            ?>              
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="type">Вид избираема</label><br>
            <select name="type" id="type" required>
           	<?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['type'];
                if ($val!=""){
                    switch ($val){
                        case "ОКН":
                        echo    '<option value="ОКН" selected >ОКН</option>
                            <option value="ЯКН">ЯКН</option>
                            <option value="Математика">Математика</option>';
                        break;
                        case "ЯКН":
                        echo    '<option value="ОКН" >ОКН</option>
                            <option value="ЯКН" selected >ЯКН</option>
                            <option value="Математика">Математика</option>';
                        break;
                        case "Математика":
                        echo    '<option value="ОКН" >ОКН</option>
                            <option value="ЯКН">ЯКН</option>
                            <option value="Математика" selected>Математика</option>';
                        break;
                    }
                }
                }
            else{
                echo '<option value="ОКН">ОКН</option>
                    <option value="ЯКН">ЯКН</option>
                    <option value="Математика">Математика</option>';
            }  
            ?>  
            </select>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="credits">Кредити</label><br>
            <?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['credits'];
                if ($val!=""){
                    echo '<input type="number" name="credits" id="credits" min="0" max="10" value="'.$val.'" >';
                }
                }
            else{
                echo '<input type="number" name="credits" id="credits" min="0" max="10" value="0" >';
            }  
            ?>                
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <div class="group">
			<label for="credits">Места</label><br>
            <?php
             if(isset($_GET['warning'])){
                $strenc= $_GET['warning'];
                $arr = unserialize(urldecode($strenc));
                $val = $arr['max'];
                if ($val!=""){
                    echo '<input type="number" name="max" id="max" min="4" max="200" value="'.$val.'" >';
                }
                }
            else{
                echo '<input type="number" name="max" id="max" min="4" max="200" value="50" >';
            }  
            ?>                
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <button class="submit" type="submit" id="submit" >Добави избираема</button>
		</form>
  </div>
  <br>
  
  
</body>

<?php
include "electives_overall_admin.html";
?>
</html>