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
            case 'student' :  break;
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
    <title>Electives</title>
</head>

<body>

<?php
    if (checkCampaign()==true){
        echo '<h3 id="not" style="display:block;">В момента текат следните кампании за записване на изборни дисциплини: </h3> </br>';
            
            $connection->beginTransaction();
            $currentDate = Date("Y-m-d");
            $query = "SELECT * FROM campaigns WHERE '".$currentDate."' >= start AND '".$currentDate."' <= finish ;";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            $found;

            echo '<div id="box3">';            
            while ($found = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo
                '<form class=form2>
                <div class="group">
                <label> <h3 class="info" id="not" style="display:block ">От '.$found['start'].' до '.$found['finish'].'</h3></label>
                </form>
                <span class="highlight"></span>
                <span class="bar"></span><br>
                </div>';    
            }
            echo '</div>';
    }
    else {
        echo '<h3 id="not" style="display:block;">В момента не текат кампании за записване на изборни дисциплини. Все пак можете да ги разгледате.</h3> </br>';
            
            $connection->beginTransaction();
            $currentDate = Date("Y-m-d");
            $query = "SELECT * FROM campaigns WHERE '".$currentDate."' <= start AND '".$currentDate."' <= finish ;";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            $found;  
            $futureCampaigns = array();
            while ($found = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($futureCampaigns,$found);
            }
            if (count($futureCampaigns)>0){
                echo '<h3 id="not" style="display:block;">Към момента предстоят следните кампании:</h3> </br>';
                echo '<div id="box3">';
            }
            foreach($futureCampaigns as $cmp){
                echo
                '<form class=form2>
                <div class="group">
                <label> <h3 class="info" id="not" style="display:block ">От '.$cmp['start'].' до '.$cmp['finish'].'</h3></label>
                </form>
                <span class="highlight"></span>
                <span class="bar"></span><br>
                </div>';      
            }
           if (count($futureCampaigns)>0){
                echo '</div>';
            }
            
    }
    
?>

</body>

<?php
include "electives_overall_student.php";
?>
</html>