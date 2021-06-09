<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
session_start();
if(!isset($_SESSION['user'])){
    header("location: ../index.php");
}

require_once("../../config.php");

$empty = false;

if(!isset($_POST['usermessage'])){
    echo "empty";
    $empty = true;
}else{
    if(ctype_space($_POST['usermessage']) || empty($_POST['usermessage'])){
        echo "empty";
        $empty = true;
    }else {
        if (!isset($_POST['receiver_id'])) {
            echo "nouser";
            $empty = true;
        } else if (empty($_POST['receiver_id'])) {
            echo "nouser";
            $empty = true;
        }
    }
}


if(!$empty){
    try{
        $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("INSERT INTO messages (sender_id,receiver_id,text,send_date) VALUES (:sender_id,:receiver_id,:text,NOW())");
        $r = $stmt->execute(array(":sender_id"=>$_POST['sender_id'],":receiver_id"=>$_POST['receiver_id'],":text"=>$_POST['usermessage']));

        echo "success";
    }catch(PDOException $e){
    }
}