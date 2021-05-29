<?php
/**
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
//Milestone Alpha purposes (possibly additional functionalities for admin for future milestones)
require('../config.php');

// Connect to DB
echo "test";

//$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
try{
    $conn = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "Connected Successfully";

}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}