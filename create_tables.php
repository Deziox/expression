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
                email char(100) NOT NULL DEFAULT '',
                username varchar(30) NOT NULL,
                password char(100) NOT NULL,
                posts varchar(1000) DEFAULT NULL,
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
        "DROP TABLE IF EXISTS messages;
            CREATE TABLE messages (
                message_id int(11) AUTO_INCREMENT PRIMARY KEY,
                sender_id int(11) NOT NULL,
                receiver_id int(11),
                text varchar(500) NOT NULL,
                send_date DATETIME NOT NULL,
                read_message BIT(1) NOT NULL DEFAULT 0,
                FOREIGN KEY (sender_id) REFERENCES users(userid),
                FOREIGN KEY (receiver_id) REFERENCES users(userid)
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS forgot_requests;
            CREATE TABLE forgot_requests (
                request_id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
                userid int(11) NOT NULL,
                reset_code char(100) NOT NULL,
                end_time DATETIME NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(userid)
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS post_tag;
            
            DROP TABLE IF EXISTS posts;
            CREATE TABLE posts (
                post_id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
                userid int(11) NOT NULL,
                title char(50) NOT NULL,
                description varchar(500) NOT NULL DEFAULT '',
                image varchar(500) DEFAULT '',
                post_time DATETIME NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(userid) ON DELETE CASCADE
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS tags;
            CREATE TABLE tags (
                tag_id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
                text varchar(500) NOT NULL DEFAULT ''
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "
            CREATE TABLE post_tag (
                post_id int(11) NOT NULL,
                tag_id int(11) NOT NULL,
                FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(tag_id) ON DELETE CASCADE
            );"
    );

    $r = $stmt->execute();

    $stmt = $db->prepare(
        "DROP TABLE IF EXISTS comments;
            CREATE TABLE comments (
                comment_id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
                userid int(11) NOT NULL,
                post_id int(11) NOT NULL,
                text varchar(100) NOT NULL,
                comment_date DATETIME NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(userid) ON DELETE CASCADE,
                FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE
            );"
    );

    $r = $stmt->execute();

}catch(PDOException $e){
    //echo "Connection failed: " . $e->getMessage();
}