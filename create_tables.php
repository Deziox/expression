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

// Connect to DB
//$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
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
                is_admin bit NOT NULL DEFAULT 0,
                PRIMARY KEY (userid)
            )"
    );
    $r = $stmt->execute();

}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}