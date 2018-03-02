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
    <title>Electives QKN Admin</title>
</head>


<?php
        if(isset($_POST['ID'])){
            $ID = $_POST['ID'];
            
            $res;
            $connection->beginTransaction();
            $query = "SELECT * from electives WHERE id=".$ID.";";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $res= $stmt->fetch(PDO::FETCH_ASSOC);
            
            $subject = $res['electiveName'];  
            $lecturer = $res['lecturerName'];  
            $type = $res['type'];  
            $credits = $res['credits'];

            $query = "DELETE FROM enrowed WHERE electiveID=".$ID.";";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            
            $query = "DELETE FROM electives WHERE id=".$ID.";";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();           
            
            require_once('mailer.php');
            notify($subject,$lecturer,$type,$credits,$max,false);
            
            header('Location: QKN_admin.php');
            exit;
        }
?>


<body>
  <h2>Изборни дисциплини - ЯКН </h2>
  
  <div id="box3">
  <?php
        $connection->beginTransaction();
        $query = "SELECT * FROM electives where type='ЯКН'  ";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $connection->commit();
      
        $record;
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            $number = $record['id'];              
            echo 
            '<form class=form2 method="POST" action="QKN_admin.php" method="POST">
            <input id="ID" name="ID" type="hidden" value="'.$number.'">
            <div class="group">
			<label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
            <button class="submit" type="submit" id="remove'.$number.'" >Премахни</button> </form>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>';
        }
        
            
  ?>
  </div>  
</body>

<?php
include "electives_QKN_admin.html";
?>
</html>