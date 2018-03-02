<?php
    require_once('config.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
    session_start();
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
    <title>My electives</title>
</head>

<body>
  <h2>Записани изборни дисциплини в тази кампания</h2>
  
  <div id="box3">
  <?php
        $connection->beginTransaction();
        $query = "SELECT id FROM users where username='".$_SESSION['userid']."';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $ID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
      
        $query = "SELECT * FROM (enrowed join electives on enrowed.electiveID = electives.id) WHERE userID=".$ID.";";
        $stmt = $connection->prepare($query);
        $stmt->execute();      
        $connection->commit();
      
        $record = array();
        $temp;
        $type = array();
        $count = 0;
        while ($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($record,$temp);
            $number = $temp['id'];
            if (isset($type[$temp['type']])){
                $type [$temp['type']]++;
            }
            else {
                $type [$temp['type']] = 1;
            }
            $count += $temp['credits'];
        }
        
        echo '<h3 id="not" style="display:block;">Общо кредити от тях: '.$count.' </h3>';
        
        foreach($type as $key=>$val){
           echo '<h4 id="not" style="display:block;">Брой '.$key.' : '.$val.' </h4>'; 
        }
        
        foreach ($record as $rec){
            echo 
            '<form class=form2>
            <div class="group">
			<label> <h4 class="info">Дисциплина: '.$rec['electiveName']."\t, Лектор: ".$rec["lecturerName"]."\t, Група: ".$rec["type"]."\t, Кредити: ".$rec["credits"]."\t, Места: ".$rec["maxSlots"]."\t, Записани: ".$rec["currentSlotsTaken"].'</h4></label>
            </form>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>';
        }
        
            
  ?>
  </div>  
</body>

<?php
include "myElectivesMenu.php";
?>
</html>