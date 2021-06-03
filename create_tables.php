<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
//Milestone Alpha purposes (possibly additional functionalities for admin for future milestones)
require('config.php');

try{
    $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "Connected Successfully";

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS users;
            CREATE TABLE users (
                userid int(11) AUTO_INCREMENT,
                email char NOT NULL DEFAULT '',
                username varchar(30) NOT NULL,
                password char NOT NULL,
                posts varchar DEFAULT NULL,
                is_admin bit NOT NULL DEFAULT 0,
                PRIMARY KEY (userid)
            )"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS chats;
            CREATE TABLE chats (
                chat_id int(11) AUTO_INCREMENT PRIMARY KEY,
                users MEDIUMTEXT NOT NULL,
                messages MEDIUMTEXT
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS message;
            CREATE TABLE message (
                message_id int(11) AUTO_INCREMENT PRIMARY KEY,
                chat_id int(11) NOT NULL,
                text varchar(500) NOT NULL,
                FOREIGN KEY (chat_id) REFERENCES chats(chat_id)
            );"
    );

    $r = $stmt->execute();

}catch(PDOException $e){
    //echo "Connection failed: " . $e->getMessage();
}