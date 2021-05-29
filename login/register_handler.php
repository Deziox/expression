<?php
    require('../config.php');

    $errors = array('email'=>'','username'=>'','password'=>'');
    $empty = false;

    if(isset($_POST['submit'])){
        if(empty($_POST['email'])){
            $errors['email'] = "An email is required";
            $empty = true;
        }
        if(empty($_POST['username'])){
            $errors['username'] = "A username is required";
            $empty = true;
        }
        if(empty($_POST['password'])){
            $errors['password'] = "Password cannot be empty";
            $empty = true;
        }
        if(!$empty){
            $email = $_POST['email'];
            $username = $_POST['username'];
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
            }
        }
    }
?>