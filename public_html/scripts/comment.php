<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
session_start();
if (!isset($_SESSION['user'])) {
    echo "nosesh";
}else{
    require_once("../../config.php");

    $empty = false;

    if (ctype_space($_POST['comment']) || empty($_POST['comment'])) {
        echo "empty";
        $empty = true;
    }

    if (!$empty) {
        try {
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("INSERT INTO comments (userid,post_id,text,comment_date) VALUES (:userid,:post_id,:text,NOW())");
            $r = $stmt->execute(array(":userid" => $_SESSION['user']['uid'], ":post_id" => $_POST['post_id'], ":text" => $_POST['comment']));
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        echo "yur mum ".$_POST['comment']." ".$_POST['post_id'];
    }
}

