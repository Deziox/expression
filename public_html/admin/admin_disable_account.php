<?php
/**
 * @var Aws\S3\S3Client $s3
 * @var string $bucket
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
session_start();
if(!isset($_SESSION['user'])){
    header("location: /login.php");
}else{
    if($_SESSION['user']['is_admin'] != 1){
        header("location: /user.php");
    }
}

if(!isset($_GET['uid'])){
    echo "empty";
}else{
    include_once "../config.php";
    $uid = $_GET['uid'];
    $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("UPDATE users SET disabled = 1 WHERE userid = :userid");
    $r = $stmt->execute(array(":userid" => $uid));
    echo "success";
}
