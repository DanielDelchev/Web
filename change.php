<?php
    require_once('config.php');
    require_once('User.php');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  
    session_start();
    if(!isset($_SESSION['userid'])){
        header('Location:login.php');
        exit;
    }
    else{
        checkExpire();
    }
       
    if(isset($_POST['old'], $_POST['repeat'], $_POST['new'])) {
  
            $connection->beginTransaction();
            
            $username = $_SESSION['userid'];
            $old = $_POST['old'];
            
            $query = "SELECT password from `users` WHERE username = '".$username."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $oldHash = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection->commit();
            
            $user = new User($username, $old, $connection);
            
            if ($user->isValid()){
                $connection->beginTransaction();
                $newPass = $_POST['new'];
                $newPassHash = password_hash($newPass, PASSWORD_DEFAULT); 
                $query = "UPDATE `users` SET `password`= '".$newPassHash."' WHERE username = '".$username."';";
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $connection->commit();
                
                session_unset();
                session_destroy();
                $val = array('changeSuccessful');
                $str = serialize($val);
                $strenc = urlencode($str); 
                header('Location: warning.php?notification='.$strenc);
                exit;
            }
            
            else{
                $val = array('wrongPassword');
                $str = serialize($val);
                $strenc = urlencode($str); 
                header('Location: changePassword.php?warning='.$strenc);
                exit;
            }
          
    }


header('Location: homePage.php');
exit;    
?>