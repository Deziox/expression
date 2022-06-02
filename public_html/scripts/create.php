<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: /login.php");
}
/**
 * @var Aws\S3\S3Client $s3
 * @var string $bucket
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
require("../../s3_config.php");
require("../../config.php");

use Aws\S3\Exception\S3Exception;

$error = "";
$tag_ids = array();
$post_id = "";
if (empty($_POST['title'])) {
    echo "err_title";
} else if (!isset($_FILES['upload']) || $_FILES['upload']['size'] <= 0) {
    echo "err_file";
} else {
    //s3 target and image filetype
    $supported_filetypes = array('gif', 'jpg', 'jpeg', 'png');
    $file_target = basename($_FILES['upload']['name']);
    $filetype = strtolower(pathinfo($file_target, PATHINFO_EXTENSION));

    if (!in_array($filetype, $supported_filetypes)) {
        echo "err_file";
    } else {

        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $description = "";
        if (!empty($_POST['message'])) {
            $description = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        }
        $tags = "";
        if (!empty($_POST['tags'])) {
            $tags = explode("#", filter_var($_POST['tags'], FILTER_SANITIZE_STRING));
        }

        // Create post in database (and tag if it doesn't exist)
        try {
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //add nonexistent tags
            for ($i = 0; $i < count($tags); $i++) {
                $tag = trim($tags[$i]);
                if ($tag == "") continue;

                $stmt = $db->prepare("SELECT * FROM tags WHERE text = :text");
                $r = $stmt->execute(array(":text" => $tag));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    $stmt = $db->prepare("INSERT INTO tags(text) VALUES (:text)");
                    $r = $stmt->execute(array(":text" => $tag));
                    array_push($tag_ids, $db->lastInsertId());
                } else {
                    array_push($tag_ids, $result['tag_id']);
                }
            }

            //create post and create post-tag relations
            if ($error == "") {
                $stmt = $db->prepare("INSERT INTO posts (userid,title,description,image,post_time) VALUES (:userid,:title,:description,'',NOW())");
                $r = $stmt->execute(array(":userid" => $_SESSION['user']['uid'], ":title" => $title, ":description" => $description));
                $post_id = $db->lastInsertId();

                foreach ($tag_ids as &$tid) {
                    $stmt = $db->prepare("INSERT INTO post_tag (post_id,tag_id) VALUES (:post_id,:tag_id)");
                    $r = $stmt->execute(array(":post_id" => $post_id, ":tag_id" => $tid));
                }
            }
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }

        //upload file to s3 bucket
        //id: userid.postnumber.<imagefiletype>

        $key = $post_id . "." . $filetype;
        try {
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key' => "images/" . $key,
                'Body' => fopen($_FILES['upload']['tmp_name'], 'rb'),
                'ACL' => 'public-read'
            ]);
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            $stmt = $db->prepare("UPDATE posts SET image = :image WHERE post_id = :post_id");
            $r = $stmt->execute(array(":image" => $key, ":post_id" => $post_id));
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }

        echo $_POST['title'] . "\n" . $_FILES['upload']['tmp_name'] . "\n" . $_POST['message'] . "\n" . $_POST['tags'] . "\n" . implode(",", $tag_ids) . "\n" . $post_id;
    }
}
?>


