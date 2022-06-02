<?php
//TODO: Implement the reset password portion of this
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 * @var \PHPMailer\PHPMailer\PHPMailer $mail
 */
session_start();
if(isset($_SESSION['user'])){
    header("location: login_success.php");
}

require('../config.php');
require('../mailer_config.php');

$errors = array('email'=>'');

$email = "";

if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $errors['email'] = "An email is required";
    }else{
        $email = $_POST['email'];
    }

    //Check first if email exists in db
    if(!array_filter($errors)){
        try{
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $r = $stmt->execute(array(":email" => $email));
            $emailresult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($stmt->rowCount() <= 0){
                $errors['email'] = "No account exists with that email.";
            }

            if(!array_filter($errors)){
                $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $reset_code = "";
                for ($i = 0; $i < 8; $i++){
                    $reset_code .= $chars[rand(0,strlen($chars)-1)];
                }

                //TODO: organize database (lots of redundancies)
                $stmt = $db->prepare("SELECT * FROM forgot_requests WHERE userid = :user_id");
                $r = $stmt->execute(array(":user_id" => $emailresult[0]['userid']));
                $requestresult = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$requestresult){
                    $stmt = $db->prepare("INSERT INTO forgot_requests (userid,reset_code,end_time) VALUES (:user_id,:reset_code,DATE_ADD(now(),interval 2 hour))");
                    $code_hash = password_hash($reset_code,PASSWORD_BCRYPT);
                    $r = $stmt->execute(array(":user_id"=>$emailresult[0]['userid'],":reset_code"=>$code_hash));


                }else{
                    $stmt = $db->prepare("UPDATE forgot_requests SET reset_code=:reset_code,end_time=DATE_ADD(now(),interval 2 hour) WHERE userid = :user_id");
                    $code_hash = password_hash($reset_code,PASSWORD_BCRYPT);
                    $r = $stmt->execute(array(":reset_code"=>$code_hash,":user_id"=>$emailresult[0]['userid']));
                }

                try{
                    $mail->addAddress($email);
                    $mail->Subject = "Expression - Password Reset";
                    $mail->Body = "<h1>Reset Code: ".$reset_code."</h1>";
                    $mail->AltBody = "Reset Code: ".$reset_code;
                    $mail->send();
                }catch(Exception $e){
                }

                header("location: ../public_html/reset_code_confirm.php?uid=".$emailresult[0]['userid']);
            }
        }catch(PDOException $e){
            //echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>