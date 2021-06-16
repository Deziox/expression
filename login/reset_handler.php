<?php
/**
 * @var array $errors
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
if(!isset($_GET['uid'])){
    header("location: login_success.php");
}
if(isset($_SESSION['user'])){
    header("location: login_success.php");
}
if(!isset($_SESSION['rcode'])){
    header("location: index.php");
}

$errors = array('password'=>'');

require('../config.php');

try{
    $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //Check if request expired or exists
    $stmt = $db->prepare("SELECT * FROM forgot_requests WHERE userid = :uid AND end_time > now()");
    $stmt->execute(array(":uid" => $_GET['uid']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$result){
        header("location: index.php");
    }else{
        if(isset($_POST['submit'])){
            $empty = false;

            if(empty($_POST['password'])){
                $errors['password'] = "Password cannot be empty";
                $empty = true;
            }

            if(!$empty){
                $password = $_POST['password'];

                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number    = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                    $errors['password'] = "Password must be at least 8 characters long and should have one upper case letter, one number, and one special character.";
                }

                if(!array_filter($errors)){
                    $stmt = $db->prepare("UPDATE users SET password=:password WHERE userid = :user_id");
                    $pass_hash = password_hash($password,PASSWORD_BCRYPT);
                    $r = $stmt->execute(array(":password"=>$pass_hash,":user_id"=>$_GET['uid']));

                    header("location: login.php");
                }
            }
        }
    }

}catch(PDOException $e){
}