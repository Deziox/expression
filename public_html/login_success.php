<?php
    session_start();
    if(isset($_SESSION['user'])){
        if($_SESSION['user']['is_admin'] == 1) {
            header("location: /admin.php");
        }else{
            header("location: /user.php");
        }
    }else{
        header("location: /login.php");
    }