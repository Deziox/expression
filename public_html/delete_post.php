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
if(!isset($_SESSION['user'])) {
    header("location: /index.php");
}else if(!isset($_POST['post_to_delete']) || !isset($_POST['post_to_delete_uid'])){
    header("location: /user.php");
}else if($_SESSION['user']['is_admin'] != 1 && $_SESSION['user']['uid'] != $_POST['post_to_delete_uid']){
    header("location: /user.php");
}else{
    include_once "../config.php";
    try {
        $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("DELETE FROM posts WHERE post_id = :post_id");
        $stmt->execute(array(":post_id" => $_POST['post_to_delete']));
    }catch(PDOException $e){
    }
    header("location: /user.php");
}
