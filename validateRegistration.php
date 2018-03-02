<?php
require_once('User.php');
require_once('mailer.php');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

session_start();

if(!isset($_SESSION['userid'])){
    if(isset($_POST['username'], $_POST['password'], $_POST['email'],$_POST['repeat'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
    
        $connection->beginTransaction();
        $query = "SELECT * FROM users WHERE username = '".$username."' and status='approved';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $name = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        $connection->beginTransaction();
        $query = "SELECT * FROM users WHERE email = '".$email."' and status='approved';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $mail = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        if ($mail!=null && $name!=null){
            $val = array('takenEmail'=>$email,'takenUsername'=>$username,'prevMail'=>$email,'prevUser'=>$username);
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: register.php?warning='.$strenc);
            exit;
        }
        
        if($mail!=null){
        //implement error dipslay for these 2 // ok
            $val = array('takenEmail'=>$email,'prevMail'=>$email,'prevUser'=>$username);
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: register.php?warning='.$strenc);
            exit;
        }
        if($name!=null){
        //implement error dipslay for these 2 // ok
            $val = array('takenUsername'=>$username,'prevMail'=>$email,'prevUser'=>$username);
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: register.php?warning='.$strenc);
            exit;
        }
        
        if($mail==null && $name==null){
            $activationCode = sha1($email.time());
            $hashed = password_hash($password, PASSWORD_DEFAULT); 
            $connection->beginTransaction();
            $query = "INSERT INTO `users` (username, password, email, status, code, role) VALUES ('".$username."', '".$hashed."','".$email."','pending','".$activationCode."', 'student')"; 
            $stmt = $connection->prepare($query);
            $stmt->execute();
            send($email,$activationCode);
            $connection->commit();

            //redirect to explaination page that the user has to validate
            // have the short menu on that page    //ok 
            $val = array('pleaseValidate');
            $str = serialize($val);
            $strenc = urlencode($str);            
            header('Location: warning.php?notification='.$strenc);
            exit;
        }
    }
    
    else if(isset($_GET['code'])){
         
        $strenc= $_GET['code'];
        $arr = unserialize(urldecode($strenc));
        $code = $arr[0];
         
        $connection->beginTransaction();
        $query = "SELECT username FROM users WHERE code = '".$code."' and status='pending';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $name = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        $connection->beginTransaction();
        $query = "SELECT email FROM users WHERE code = '".$code."' and status='pending';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $mail = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        $connection->beginTransaction();
        $query = "SELECT * FROM users WHERE username = '".$name['username']."' and status='approved';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $name = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        $connection->beginTransaction();
        $query = "SELECT * FROM users WHERE email = '".$mail['email']."' and status='approved';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $mail = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        $connection->beginTransaction();
        $query = "SELECT * FROM users WHERE code = '".$code."' and status='pending';";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $exist = $stmt->fetch(PDO::FETCH_ASSOC);
        $connection->commit();
        
        if($exist==null){
            $val = array('invalidCode');
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: warning.php?warning='.$strenc);
            exit;
        }     
        
        else if($mail!=null && $name!=null){        
            $connection->beginTransaction();
            $query = "DELETE FROM users WHERE code = '".$code."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            $val = array('takenEmail','takenUsername');
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: warning.php?warning='.$strenc);
            exit;
        }
        
        
        else if($mail!=null){        
            $connection->beginTransaction();
            $query = "DELETE FROM users WHERE code = '".$code."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            $val = array('takenEmail');
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: warning.php?warning='.$strenc);
            exit;
        }
        else if($name!=null){
            $connection->beginTransaction();
            $query = "DELETE FROM users WHERE code = '".$code."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $connection->commit();
            $val = array('takenUsername');
            $str = serialize($val);
            $strenc = urlencode($str);
            header('Location: warning.php?warning='.$strenc);
            exit;
        }        
        
        $OK = (($mail == null) && ($name == null) && ($exist != null));
        
        if($OK==true){
            
            $connection->beginTransaction();
            
            $query = "UPDATE `users` SET `status`='approved' WHERE code = '".$code."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            
            $query = "UPDATE `users` SET `stamp`= CURRENT_TIMESTAMP WHERE code = '".$code."';";
            $stmt = $connection->prepare($query);
            $stmt->execute();
 
            $connection->commit();
            
            //TODO get semester, role , program from other table (existing fabricated) and assign them to this user // ok   
            $val = array('successfulValidation');
            $str = serialize($val);
            $strenc = urlencode($str);            
            header('Location: warning.php?notification='.$strenc);
            exit;                
       
        }           
        
    }    
}



//rnd access uncomment this in the end(after redirects for other cases are done up) // ok
header('Location: homePage.php');
exit;
?>
