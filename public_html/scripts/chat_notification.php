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

        $stmt = $db->prepare("SELECT * FROM messages 
                                    JOIN users ON messages.sender_id = users.userid
                                    WHERE (receiver_id = :receiver_id) AND read_message = 0");
        $stmt->execute(array(":receiver_id" => $_SESSION['user']['uid']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $unread_messages = array();

        if(!$result){
            $output = "empty";
        }else{
            while($result){
                if(!in_array($result['username'],$unread_messages)) {
                    array_push($unread_messages, $result['username']);
                }
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            $output = implode(",",$unread_messages);
        }
    }catch(PDOException $e){
        $output = "server_error";
    }
    echo $output;
}else{
    echo "nosesh";
}