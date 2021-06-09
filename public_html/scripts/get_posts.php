<?php
/**
 * @var Aws\S3\S3Client $s3
 * @var string $bucket
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

require("../../s3_config.php");
session_start();
if (isset($_SESSION['user'])) {
    include_once "../../config.php";
    $output = "";
    $tag = $_GET['tag'];
    if(empty($tag)) {
        try {
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT *
                                        FROM posts
                                        INNER JOIN users
                                        ON posts.userid = users.userid
                                        ORDER BY post_time DESC");
            $r = $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $checked_posts = "";
            if (!$result) {
                $output = "empty";
            } else {
                while ($result) {
                    $checked_posts .= "'" . $result['post_id'] . "'";

                    $cmd = $s3->getCommand('GetObject', [
                        'Bucket' => $bucket,
                        'Key' => "images/" . $result['image']
                    ]);
                    $request = $s3->createPresignedRequest($cmd, '+60 minutes');
                    $img_url = (string)$request->getUri();

                    $output .= '<div class="card">
                    <img class="card-img-top" src="' . $img_url . '" alt="Card image cap">
                    <div class="card-body">
                    <h3 class="card-title font-weight-bold">' . $result['title'] . '</h3>
                    <label>@' . $result['username'] . '</label>
                    <p class="card-text">' . $result['description'] . '</p>';

                    $tag_output = "";
                    $stmt = $db->prepare("SELECT 
                                                    posts.post_id,
                                                    tags.tag_id,
                                                    tags.text
                                                FROM posts 
                                                LEFT JOIN post_tag ON posts.post_id = post_tag.post_id
                                                RIGHT JOIN tags ON tags.tag_id = post_tag.tag_id
                                                WHERE post_tag.post_id = :post_id");
                    $r = $stmt->execute(array(":post_id" => $result['post_id']));
                    $tag_result = $stmt->fetch(PDO::FETCH_ASSOC);

                    while ($tag_result) {
                        $tag_output .= "#" . $tag_result['text'] . " ";
                        $tag_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    }

                    $output .= '<p class="card-text">' . $tag_output . '</p><div class="comment_area scroll overflow-auto" id="comment_area_' . $result['post_id'] . '">';

                    $output .= '</div></div>
                    <form class="comment_form" id="comment_form_' . $result['post_id'] . '">
                    <input type="hidden" class="post_id" name="post_id" value="' . $result['post_id'] . '">
                    <input type="hidden" id="comment_ids_' . $result['post_id'] . '" name="comment_ids" value="">
                    <input type="text" class="form-control comment" placeholder="Comment" name="comment">
                    <div class="container">
                    <button type="button" class="comment_post btn btn-primary" name="post" id="post_' . $result['post_id'] . '" onclick="sendComment(' . $result['post_id'] . ')">Post</button>
                    </div>
                    </form>
                    <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                    </div>
                    <br><br>';

                    $stmt = $db->prepare("SELECT *
                                        FROM posts
                                        INNER JOIN users
                                        ON posts.userid = users.userid
                                        WHERE post_id NOT IN (" . $checked_posts . ")
                                        ORDER BY post_time DESC");
                    $r = $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $checked_posts .= ",";
                    }
                }
            }
        } catch (Exception $e) {
            //echo "Connection failed: " . $e->getMessage();
            $output = "server_error";
        }
    } else {
        try {
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT posts.post_id,
                                               posts.userid,
                                               posts.title,
                                               posts.description,
                                               posts.image,
                                               posts.post_time
                                        FROM posts
                                        INNER JOIN post_tag  
                                        ON posts.post_id = post_tag.post_id 
                                        INNER JOIN tags 
                                        ON tags.tag_id = post_tag.tag_id
                                        WHERE tags.text = ?
                                        ORDER BY post_time DESC");

            $r = $stmt->execute([$tag]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $checked_posts = "";
            if (!$result) {
                $output = "empty";
            } else {
                while ($result) {
                    $checked_posts .= "'" . $result['post_id'] . "'";

                    $cmd = $s3->getCommand('GetObject', [
                        'Bucket' => $bucket,
                        'Key' => "images/" . $result['image']
                    ]);
                    $request = $s3->createPresignedRequest($cmd, '+60 minutes');
                    $img_url = (string)$request->getUri();

                    $output .= '<div class="card">
                    <img class="card-img-top" src="' . $img_url . '" alt="Card image cap">
                    <div class="card-body">
                    <h3 class="card-title font-weight-bold">' . $result['title'] . '</h3>
                    <label>@' . $result['username'] . '</label>
                    <p class="card-text">' . $result['description'] . '</p>';

                    $tag_output = "";
                    $stmt = $db->prepare("SELECT 
                                                    posts.post_id,
                                                    tags.tag_id,
                                                    tags.text
                                                FROM posts 
                                                LEFT JOIN post_tag ON posts.post_id = post_tag.post_id
                                                RIGHT JOIN tags ON tags.tag_id = post_tag.tag_id
                                                WHERE post_tag.post_id = :post_id");
                    $r = $stmt->execute(array(":post_id" => $result['post_id']));
                    $tag_result = $stmt->fetch(PDO::FETCH_ASSOC);

                    while ($tag_result) {
                        $tag_output .= "#" . $tag_result['text'] . " ";
                        $tag_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    }

                    $output .= '<p class="card-text">' . $tag_output . '</p><div class="comment_area scroll overflow-auto" id="comment_area_' . $result['post_id'] . '">';

                    $output .= '</div></div>
                    <form class="comment_form" id="comment_form_' . $result['post_id'] . '">
                    <input type="hidden" class="post_id" name="post_id" value="' . $result['post_id'] . '">
                    <input type="hidden" id="comment_ids_' . $result['post_id'] . '" name="comment_ids" value="">
                    <input type="text" class="form-control comment" placeholder="Comment" name="comment">
                    <div class="container">
                    <button type="button" class="comment_post btn btn-primary" name="post" id="post_' . $result['post_id'] . '" onclick="sendComment(' . $result['post_id'] . ')">Post</button>
                    </div>
                    </form>
                    <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                    </div>
                    <br><br>';


                }
            }
        } catch (Exception $e) {
            //echo "Connection failed: " . $e->getMessage();
            $output = "server_error";
        }

    }
    echo $output;
} else {
    //header("location: ../public_html/index.php");
    echo "nosesh";
}


