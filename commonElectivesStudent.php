<?php
    require_once('config.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
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
    <title>Electives <?php echo $kind2; ?></title>
</head>




<body>

  <h2><?php echo $kind1; ?> изборни дисциплини</h2>

  
  <?php
        if(isset($_POST['Unsign_ID']) && isset($_POST['userID'])){
        
        if (checkCampaign()==false){header('Location:'.$kind2.'_student.php'); exit;}
            
            $userID = $_POST['userID'];
            $electiveID = $_POST['Unsign_ID'];
            
            $connection->beginTransaction();
            $query = "SELECT * FROM enrowed WHERE userID='".$userID."' AND electiveID='".$electiveID."';";
            $substmt = $connection->prepare($query);
            $substmt->execute();
            $found = $substmt->fetch(PDO::FETCH_ASSOC);
            if ($found!=null){
               $query = "DELETE FROM enrowed WHERE userID='".$userID."' AND electiveID='".$electiveID."';";
               $substmt = $connection->prepare($query);
               $substmt->execute();
               $query = "UPDATE electives SET currentSlotsTaken = (currentSlotsTaken-1) WHERE id='".$electiveID."';";
               $substmt = $connection->prepare($query);
               $substmt->execute();
            }
            $connection->commit();
        
        }
    
    
        else if(isset($_POST['Sign_ID']) && isset($_POST['userID'])){
        
        if (checkCampaign()==false){header('Location:'.$kind2.'_student.php'); exit;}
        
        // check if not signed && slots free, if slots free and not signed, sign me, increase count of signed
        // if already signed, send error with encoded get
        // if no more slots, send error with  encoded get  

            $warn = false;
            $userID = $_POST['userID'];
            $electiveID = $_POST['Sign_ID'];
            
            $connection->beginTransaction();
            $query = "SELECT * FROM enrowed WHERE userID='".$userID."' AND electiveID='".$electiveID."';";
            $substmt = $connection->prepare($query);
            $substmt->execute();
            $found = $substmt->fetch(PDO::FETCH_ASSOC);
            if ($found==null){
                
                $query = "SELECT * FROM electives where id='".$electiveID."' ;";
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $currentSlotsTaken = $result['currentSlotsTaken'];
                $maxSlots = $result ['maxSlots'];
        
                if ($maxSlots==$currentSlotsTaken){
                    $warn = true;
                }
                else{
                   $query = "INSERT INTO `enrowed` (electiveID, userID) VALUES ('".$electiveID."','".$userID."');"; 
                   $substmt = $connection->prepare($query);
                   $substmt->execute();
                   $query = "UPDATE electives SET currentSlotsTaken = (currentSlotsTaken+1) WHERE id='".$electiveID."';";
                   $substmt = $connection->prepare($query);
                   $substmt->execute();
                }
                
            }
            $connection->commit();
        
            if ($warn){
                $val = array('type'=>"full",'electiveID'=>$electiveID);
                $str = serialize($val);
                $strenc = urlencode($str);
                header('Location: '.$kind2.'_student.php?warning='.$strenc);   
            }        
        }
    
        else if(isset($_GET['warning'])){

        $strenc= $_GET['warning'];
        $arr = unserialize(urldecode($strenc));
        $msg = "";
        $type = $arr['type'];
        switch ($type){
        case 'full':
            
            $connection->beginTransaction();
            $query = "SELECT electiveName FROM electives where id='".$arr['electiveID']."';";
            $substmt = $connection->prepare($query);
            $substmt->execute();
            $found = $substmt->fetch(PDO::FETCH_ASSOC);
            $msg = "Вече няма свободни места за избираемата '".$found['electiveName']."' .Вие НЕ сте записан/а за нея.</br>";
            $connection->commit();break;
        }
        echo '<h3 id="err" style="display:block;">'.$msg.'</h3>';
      }
    
?>
  
  
<?php

    $connection->beginTransaction();
    $query = "SELECT * FROM electives where type='".$kind1."' ;";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $connection->commit();
    $record;

    if (checkCampaign()==true){                     
        $connection->beginTransaction();
        $query = "SELECT id FROM users where username='".$_SESSION['userid']."';";
        $substmt = $connection->prepare($query);
        $substmt->execute();
        $userID = $substmt->fetch(PDO::FETCH_ASSOC)['id'];
        $connection->commit();
        $electiveID; 
        
        echo '<div id="box3">';
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            $electiveID = $record['id'];
            
            $status = "";
            
            $max = $record['maxSlots'];
            $current = $record['currentSlotsTaken'];
            $full = false;
            if ($current == $max){
                $full = true;
            }
            
            $signed = "";
            $connection->beginTransaction();
            $query = "SELECT * FROM enrowed WHERE userID='".$userID."' AND electiveID='".$electiveID."';";
            $substmt = $connection->prepare($query);
            $substmt->execute();
            $found = $substmt->fetch(PDO::FETCH_ASSOC);
            $connection->commit();
            
            ($found!=null) ? $signed=true : $signed=false;            
            
            if ($full && $signed){
                $status = "signedFull";
            }
            else if ($full && !$signed){
                $status = "unsignedFull";
            }
            else {
                $signed ? $status="unsign" : $status="sign";
            }
            
            
            
            switch($status){
                
                case "signedFull":
                         
                     echo 
                    '<form class=form2 method="POST" action="'.$kind2.'_student.php" method="POST">
                    <input id="Unsign_ID" name="Unsign_ID" type="hidden" value="'.$electiveID.'">
                    <input id="userID" name="userID" type="hidden" value="'.$userID.'">
                    <div class="group">
                    <label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
                    <span class="highlight"></span>
                    <span class="bar"></span><br>
                    <button class="submit" type="submit" id="remove" >Отпиши</button> </form>
                    </div>
                    </form>';                 
                break;

                case "unsignedFull":
                
                     echo 
                    '<form class=form2>
                    <div class="group">
                    <label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
                    <span class="highlight"></span>
                    <span class="bar"></span><br>
                    </div>
                    </form>';             
                break;
                                
                case "unsign":
                
                     echo 
                    '<form class=form2 method="POST" action="'.$kind2.'_student.php" method="POST">
                    <input id="Unsign_ID" name="Unsign_ID" type="hidden" value="'.$electiveID.'">
                    <input id="userID" name="userID" type="hidden" value="'.$userID.'">
                    <div class="group">
                    <label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
                    <span class="highlight"></span>
                    <span class="bar"></span><br>
                    <button class="submit" type="submit" id="remove" >Отпиши</button> </form>
                    </div>
                    </form>';             
                break;
                        
                case "sign":
                
                     echo 
                    '<form class=form2 method="POST" action="'.$kind2.'_student.php" method="POST">
                    <input id="Sign_ID" name="Sign_ID" type="hidden" value="'.$electiveID.'">
                    <input id="userID" name="userID" type="hidden" value="'.$userID.'">
                    <div class="group">
                    <label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
                    <span class="highlight"></span>
                    <span class="bar"></span><br>
                    <button class="submit" type="submit" id="remove" >Запиши</button> </form>
                    </div>
                    </form>';             
                break;
                                
                default: break;
            
            }
      }
      echo '</div>';
         
    }
    
    else{
        echo '<div id="box3">';
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
             echo 
            '<form class=form2>
            <div class="group">
			<label for="remove"> <h4 class="info">Дисциплина: '.$record['electiveName']."\t, Лектор: ".$record["lecturerName"]."\t, Група: ".$record["type"]."\t, Кредити: ".$record["credits"]."\t, Места: ".$record["maxSlots"]."\t, Записани: ".$record["currentSlotsTaken"].'</h4></label>
            <span class="highlight"></span>
            <span class="bar"></span><br>
			</div>
            </form>';
      }
      echo '</div>';   
    }

?>

</body>

<?php
include "".$kind2."_student_menu.html";
?>
</html>