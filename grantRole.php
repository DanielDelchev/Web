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
        if($_SESSION['role']!='administrator'){
            header('Location:homePage.php');
            exit;
        }
    }
       
    if(isset($_POST['email'], $_POST['role'])) {
            
            $mail = $_POST['email'];
            $role = $_POST['role'];
            
            $connection->beginTransaction();
            $query = "SELECT username from `users` WHERE email = '".$mail."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $found = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection->commit();
            
            
            if ($found==null){
                $val = array('noSuchUserEmail');
                $str = serialize($val);
                $strenc = urlencode($str); 
                header('Location: changeRole.php?warning='.$strenc);
                exit;
            }
            
            else if($found['username'] == $_SESSION['userid']){
                $val = array('self');
                $str = serialize($val);
                $strenc = urlencode($str); 
                header('Location: changeRole.php?warning='.$strenc);
                exit;
            }
            
            else{
                $connection->beginTransaction();
                $query = "UPDATE `users` SET `role`= '".$role."' WHERE email = '".$mail."';";
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $connection->commit();
                
                $val = array('successfulRoleChange');
                $str = serialize($val);
                $strenc = urlencode($str); 
                header('Location: changeRole.php?notification='.$strenc);
                exit;
            }
    }

header('Location: homePage.php');
exit;    
?>