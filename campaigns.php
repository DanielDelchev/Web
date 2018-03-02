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
    <title>Campaigns</title>
</head>


<?php
        if(isset($_POST['ID'])){
            $ID = $_POST['ID'];     
            $connection->beginTransaction();
            $query = "DELETE FROM campaigns WHERE id=".$ID.";";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            header('Location: campaigns.php');
            exit;
        }
        
        else if (isset($_POST['from'])&&isset($_POST['to'])){
            $from = $_POST['from'];     
            $to = $_POST['to'];
            $res;
            
            $connection->beginTransaction();
            $query = "SELECT id FROM `users` WHERE username='".$_SESSION['userid']."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $connection->commit();
            
            $connection->beginTransaction();
            $query = "INSERT INTO `campaigns` (adminID, start, finish) VALUES (".$res.",'".$from."','".$to."');";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            
            header('Location: campaigns.php');
            exit;
        }
?>


<body>
  <h2>Кампании</h2>
  
  <h3 id="err" style="display:none;">placeholder</h3>';
  <div id="box">
        <form class="form1" method="POST" action="campaigns.php" onSubmit="return validateDates()">
			<div class="group">
			<label for="passLogin">Начало (начален час 00:01)</label><br>
			<input type="date" name="from" id="from" required/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
			<div class="group">
			<label for="passLogin">Край (краен час 23:59)</label><br>
			<input type="date" name="to" id="to" required/>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            <button class="submit" type="submit" id="submit" >Добави</button>
		</form>
  </div>
  <br>
  
  <div id="box2">
  <?php
        $connection->beginTransaction();
        $query = "SELECT * FROM campaigns ORDER BY start DESC;  ";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $connection->commit();
      
        $record;
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            $number = $record['id'];              
            echo 
            '<form class=form2 method="POST" action="campaigns.php" method="POST">
            <input id="ID" name="ID" type="hidden" value="'.$number.'">
            <div class="group">
			<label for="remove"> <h4 class="info">Начало: '.$record['start']."\t,Край: ".$record["finish"].'</h4></label>
            <button class="submit" type="submit" id="remove'.$number.'" >Премахни</button> </form>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>';
        }
  ?>
  </div>
  
  
</body>

<?php
include "admin_campaigns_menu.html";
?>
</html>