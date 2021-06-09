<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
if (isset($_SESSION['user'])) {
    include_once "../../config.php";
    $output = "";
    try{
        $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $message_ids = explode(",",$_POST['message_ids']);

        $stmt = $db->prepare("SELECT * FROM messages 
                                    WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id)
                                    ORDER BY message_id ASC");
        $stmt->execute(array(":sender_id" => $_POST['sender_id'],
            ":receiver_id"=>$_POST['receiver_id'],));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            $output = "empty";
        }else{
            while($result){
                if(!in_array($result['message_id'],$message_ids)){
                    if($result['sender_id'] === $_POST['sender_id']){
                        $output = '<div class="d-flex flex-row p-3">
                                    <input type="hidden" class="message_id" value="'.$result['message_id'].'">
                                    <div class="bg-white mr-2 p-3" id="userMessage"><span class="text-muted">'.$result['text'].'</span>
                                    </div>
                                    <i class="fas fa-user-friends"></i>
                                    </div>' . $output;
                    }else{
                        $output = '<div class="container d-flex justify-content center">
                                    <div class="d-flex flex-row p-3 col-md-12" id="friendMessage"><i
                                        class="fas fa-user-friends"></i>
                                    <input type="hidden" class="message_id" value="'.$result['message_id'].'">
                                    <div class="chat ml-2 p-3">'.$result['text'].'</div>
                                    </div>
                                    </div>' . $output;
                    }
                }
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }catch(PDOException $e){
        $output = "server_error";
    }
    echo $output;
}else{
    echo "nosesh";
}