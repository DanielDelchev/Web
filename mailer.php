<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer</title>
</head>

<?php
require_once('config.php');
function send($receiver,$code){
    require_once('config.php');
    require_once('./mail/class.phpmailer.php');
    date_default_timezone_set('Etc/UTC');
    
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = "MailSenderFMI@gmail.com";
        $mail->Password = "MailSenderFMI1";
        $mail->setFrom('MailSenderFMI@gmail.com', 'Mail Sender');
        $mail->addReplyTo('MailSenderFMI@gmail.com', 'Mail Sender');
        $mail->addAddress($receiver, '');
        $mail->Subject = 'Account validation.';
                

        $val = array($code);
        $str = serialize($val);
        $strenc = urlencode($str);
        
        global $IP;
        global $emailPort;
        global $rootDir;
        
        $link = $IP.':'.$emailPort.'/'.$rootDir.'/validateRegistration.php?code='.$strenc;
        $body='Привет, <br/> <br/> Моля посетете следния линк за да активирате профила си. В случай, че преди да валидирате профила, 
        друг профил е бил валидиран със същия e-mail или потребителско име, ще трябва да направите нова регистрация.<br/> <br/> <a href="'.$link.'" target=_blank>'.$link.'</a>';
        
        $mail->CharSet = 'UTF-8';
        $mail->IsHTML(true);
        $mail->Body = $body;
        $mail->send();
        
/*        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }    
*/
}



function notify($name,$lecturer,$type,$credits,$max,$new){
    require_once('config.php');
    require_once('./mail/class.phpmailer.php');
    date_default_timezone_set('Etc/UTC');
    
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = "MailSenderFMI@gmail.com";
        $mail->Password = "MailSenderFMI1";
        $mail->setFrom('MailSenderFMI@gmail.com', 'Mail Sender');
        $mail->addReplyTo('MailSenderFMI@gmail.com', 'Mail Sender');
        
        global $connection;

      
        $connection->beginTransaction();
        $query = "SELECT DISTINCT email FROM users WHERE role='student'";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $connection->commit();
      
        $record;
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            $mail->addAddress($record['email'], '');
        }            
        
        if ($new==true){
        
            $mail->Subject = 'New elective.';
            $body='Привет, <br/> <br/> Добавена е нова избириаема дисциплина: '.$name.' водена от '.$lecturer.' от тип '.$type.' за '.$credits.' кредити. Местата за нея са '.$max.'.';
        }
        else {
            $mail->Subject = 'Removed elective.';
        
            $body='Привет, <br/> <br/> Премахната е избириаемата дисциплина: '.$name.' водена от '.$lecturer.' от тип '.$type.' за '.$credits.' кредити.<br/>
            Ако има активна кампания и Вие сте записали тази дисциплина, ще бъдете автоматично отписани.Ако няма активна кампания, просто сте уведомени за промяната.';
        }
        
        $mail->CharSet = 'UTF-8';
        $mail->IsHTML(true);
        $mail->Body = $body;
        $mail->send();
}


?> 
