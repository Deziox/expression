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
    include("get_recommendation.php");
    $output = "";
    if(isset($_GET['id'])) {
        try {
            $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT *
                                        FROM posts 
                                        WHERE userid = :userid
                                        ORDER BY post_time DESC");
            $r = $stmt->execute(array(":userid"=>$_GET['id']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $checked_posts = "";
            if (!$result) {
                $output = "no_user";
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
                    <a href="/profile.php?id='.$result['userid'].'"><label>@' . $result['username'] . '</label></a>
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
                    $recommender_tags = "";

                    while ($tag_result) {
                        $tag_output .= "#" . $tag_result['text'] . " ";
                        $recommender_tags .= $tag_result['text'];
                        $tag_result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($tag_result){
                            $recommender_tags .= ",";
                        }
                    }

                    $output .= '<p class="card-text">' . $tag_output . '</p><div class="comment_area scroll overflow-auto" id="comment_area_' . $result['post_id'] . '">';

                    $output .= '</div></div>
                    <form class="comment_form" id="comment_form_' . $result['post_id'] . '">
                    <input type="hidden" class="post_id" name="post_id" value="' . $result['post_id'] . '">
                    <input type="hidden" id="comment_ids_' . $result['post_id'] . '" name="comment_ids" value="">
                    <input type="text" class="form-control comment"  placeholder="Comment" name="comment">
                    <div class="container">
                    <button type="button" class="comment_post btn btn-primary" name="post" id="post_' . $result['post_id'] . '" onclick="sendComment(' . $result['post_id'] . ')">Post</button>
                    </div>
                    </form><div class="card-body text-center"><h4>Song Recommendation:</h4>';

                    if(!isset($_SESSION['user']['refresh_code'])){
                        $output .= '<a type="button" class="btn btn-success chat-send" id="chat-send" href="./scripts/spotify-login.php"><i class="fab fa-spotify"></i>  Log in to Spotify</a>';
                    }else{
                        $output .= get_recommendation($recommender_tags);
                    }
                    $post_time = strtotime($result['post_time']);
                    $formatted_time = date('m/d/y',$post_time);
                    $output .= '</div>
                    <div class="card-footer">
                    <small>Posted ' . $formatted_time . '</small>
                    </div>
                    </div>
                    <br><br>';

                    $stmt = $db->prepare("SELECT *
                                        FROM posts
                                        WHERE post_id NOT IN (" . $checked_posts . ") AND userid = :userid
                                        ORDER BY post_time DESC");
                    $r = $stmt->execute(array(":userid"=>$_GET['id']));
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
    }
    echo $output;
} else {
    //header("location: ../public_html/index.php");
    echo "nosesh";
}


