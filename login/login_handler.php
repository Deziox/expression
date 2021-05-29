<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
session_start();
if(isset($_SESSION['user'])){
    header("location:index.php");
}

require("../config.php");
$errors = array('email'=>'','password'=>'');
$empty = false;

if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $errors['email'] = "Email cannot be empty";
        $empty = true;
    }
    if(empty($_POST['password'])){
        $errors['password'] = "Password cannot be empty";
        $empty = true;
    }
    if(!$empty){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pass_hash = password_hash($password,PASSWORD_BCRYPT);
    }

    if(!array_filter($errors)){
        try{
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            echo "Connected Successfully";

            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $rpass = $result['password'];

            if(!$result){
                $errors['email'] = "No account exists with that email.";
            }else{
                if(password_verify($password,$rpass)){
                    $_SESSION['user'] = array(
                        "uid" => $result['userid'],
                        "email" => $result['email'],
                        "username" => $result['username'],
                        "is_admin" => $result['is_admin']
                    );
                    header("location: ../login/login_success.php");
                }else{
                    $errors['password'] = "Password is invalid";
                }
            }
        }catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }
}