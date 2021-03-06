<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
    session_start();
    if(isset($_SESSION['user'])){
        header("location: login_success.php");
    }
    require('../config.php');

    $errors = array('email'=>'','username'=>'','password'=>'','confirm'=>'');
    $empty = false;

    $username = "";
    $email = "";

    if(isset($_POST['submit'])){
        if(empty($_POST['email'])){
            $errors['email'] = "An email is required";
            $empty = true;
        }else{
            $email = $_POST['email'];
        }
        if(empty($_POST['username'])){
            $errors['username'] = "A username is required";
            $empty = true;
        }else{
            $username = $_POST['username'];
        }

        if(empty($_POST['password'])){
            $errors['password'] = "Password cannot be empty";
            $empty = true;
        }

        if(!$empty){
            $password = $_POST['password'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Not a Valid Email";
            }

            if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
                $errors['username'] = "Username cannot have any special symbols";
            }

            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                $errors['password'] = "Password must be at least 8 characters long and should have one upper case letter, one number, and one special character.";
            }else{
                if($password != $_POST['confirm-password']) {
                    $errors['confirm'] = "Passwords must match to continue";
                }
            }
        }

        //Check first if user or email already exists in db
        if(!array_filter($errors)){
            try{
                $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
                $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                //echo "Connected Successfully";

                $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
                $r = $stmt->execute(array(":email" => $email));
                $emailresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($stmt->rowCount() > 0){
                    $errors['email'] = "Account already exists under that email address";
                }

                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
                $r = $stmt->execute(array(":username"=>$username));
                $userresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($stmt->rowCount() > 0){
                    //TODO: Use an api to give suggestions for new usernames
                    $errors['username'] = "Username is already taken, please choose another";
                }

                if(!array_filter($errors)){
                    $stmt = $db->prepare("INSERT INTO users (email,username,password) VALUES (:email,:username,:password)");
                    $pass_hash = password_hash($password,PASSWORD_BCRYPT);
                    $r = $stmt->execute(array(":email"=>$email,":username"=>$username,":password"=>$pass_hash));
                    header("location: login.php");
                }
            }catch(PDOException $e){
                //echo "Connection failed: " . $e->getMessage();
            }
        }
    }
?>