<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
if (isset($_SESSION['user'])) {
    include "../../config.php";
    $output = "";
    try{
        $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM users 
                                    WHERE (username = :username)");
        $stmt->execute(array(":username" => $_POST['chat-receiver-username']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            $output = "nouser";
        }else{
            if($result['userid'] == $_SESSION['user']['uid']){
                $output = "empty";
            }else {
                $output = $result['userid'];
            }
        }
    }catch(PDOException $e){
        //echo "Connection failed: " . $e->getMessage();
        $output = "server_error";
    }
    echo $output;
}else{
    echo "nosesh";
    //header("location: ../public_html/index.php");
}