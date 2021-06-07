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
    try {
        $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM posts
                                    ORDER BY post_time DESC");
        $r = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $img_result = $s3->listObjects(array('Bucket'=>$bucket));

        if (!$result) {
            $output = "empty";
        } else {
            while ($result) {
                $cmd = $s3->getCommand('GetObject',[
                    'Bucket' => $bucket,
                    'Key' => "images/".$result['image']
                ]);
                $request = $s3->createPresignedRequest($cmd,'+60 minutes');
                $img_url = (string)$request->getUri();

                $output .= '<div class="card">
                <img class="card-img-top" src="'.$img_url.'" alt="Card image cap">
                <div class="card-body">
                <h3 class="card-title font-weight-bold">'.$result['title'].'</h3>
                <label>'.'user_name'.'</label>
                <p class="card-text">'.$result['description'].'</p>
                <form>
                <div class="form-group row">
                    <label for="readcomments" class="col-form-label"><h3>Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Impedit molestias obcaecati voluptatibus optio dignissimos enim autem
                            tenetur nisi. Molestiae et dolorem voluptatibus voluptatem corporis maxime quasi, ullam
                            aliquid cum harum dolorum sapiente laudantium inventore dolor consectetur facilis fugit,
                            officia, nobis saepe tempora quas ea autem sed. Sapiente ratione voluptas dolorum.</h3>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="readcomments"
                               value="Some comment">
                    </div>
                </div>
                </form>
                </div>
                <input type="text" class="form-control" placeholder="Comment">
                <div class="container">
                <button type="submit btn-primary">Post</button>
                </div>
                <div class="card-footer">
                <small class="text-muted">Last updated 3 mins ago</small>
                </div>
                </div>
                <br>';
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    } catch (Exception $e) {
        //echo "Connection failed: " . $e->getMessage();
        $output = "server_error";
    }
    echo $output;
} else {
    //header("location: ../public_html/index.php");
    echo "nosesh";
}