<?php
    session_start();
    if(isset($_SESSION['user'])){
        if($_SESSION['user']['is_admin'] == 1) {
            header("location:../public_html/admin.php");
        }else{
            header("location:../public_html/user.php");
        }
    }else{
        header("location:../public_html/login.php");
    }