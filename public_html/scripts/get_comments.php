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

        $comment_ids = explode(",",$_POST['comment_ids']);
        $checked_comments = "";
        for($i = 0; $i < count($comment_ids); $i++){
            $checked_comments .= "'".$comment_ids[$i]."'";
            if($i != count($comment_ids) - 1){
                $checked_comments .= ",";
            }
        }

        $stmt = $db->prepare("SELECT * FROM comments 
                                    JOIN users ON comments.userid = users.userid
                                    WHERE post_id = :post_id AND comment_id NOT IN (".$checked_comments.")
                                    ORDER BY comment_id DESC");
        $stmt->execute(array(":post_id" => $_POST['post_id']));
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$comment){
            $output = "empty";
        }else{
            while($comment){
                $output .= '
                                <div class="form-group row">
                                        <input type="hidden" class="comment_id" name="comment_id" value="'.$comment['comment_id'].'">
                                        <label for="readcomments" class="col-form-label"><h3 style="margin-left: 12px;">@' . $comment['username'] . '</h3>
                                        </label>
                                        <div class="col-sm-7">
                                            <div class="col-form-label">' . $comment['text'] . '</div>
                                        </div>
                                        <div class="col-sm">
                                            <input type="text" readonly class="form-control-plaintext" id="readcomments"
                                                value="' . $comment['comment_date'] . '">
                                        </div>
                                </div>
                                ';
                $comment = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }catch(PDOException $e){
        $output = "server_error";
    }
    echo $output;
}else{
    echo "nosesh";
}