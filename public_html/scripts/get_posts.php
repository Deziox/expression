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

        $stmt = $db->prepare("SELECT *
                                    FROM posts
                                    INNER JOIN users
                                    ON posts.userid = users.userid
                                    ORDER BY post_time DESC");
        $r = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $img_result = $s3->listObjects(array('Bucket' => $bucket));

        if (!$result) {
            $output = "empty";
        } else {
            while ($result) {
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

                //display all comments
                $output .= '<div class="scroll overflow-auto"><form>
                <div class="form-group row">
                    <label for="readcomments" class="col-form-label"><h3 style="margin-left: 12px;">@someuser</h3>
                    </label>
                    <div class="col-sm-7">
                        <div class="col-form-label">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque hendrerit blandit augue, quis vehicula urna hendrerit in. Nam congue libero quis augue facilisis sagittis. Sed egestas libero sit amet metus imperdiet efficitur. Nullam fermentum, sem id eleifend facilisis, eros sapien porttitor augue, vitae gravida lorem turpis quis nisl. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla a augue vitae massa facilisis euismod. Aliquam vitae pretium lorem. Nullam egestas velit et massa interdum, in mattis justo iaculis. Cras lacinia, diam id rhoncus suscipit, arcu ligula porttitor justo, at vestibulum mi diam id libero. Duis ac libero rhoncus, vehicula nulla id, posuere risus. Nulla lectus orci, ultrices quis maximus at, ornare at urna. Vivamus nec finibus mi. Nam venenatis dignissim mauris. Nam tristique aliquam mauris, accumsan feugiat nisl molestie a. Maecenas lorem quam, pellentesque id sagittis nec, iaculis vel erat. Sed in mollis purus.</div>
                    </div>
                    <div class="col-sm">
                    <input type="text" readonly class="form-control-plaintext" id="readcomments"
                               value="2:05 pm 06.07.21">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="readcomments" class="col-form-label"><h3 style="margin-left: 10px;">@someuser</h3>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="readcomments"
                               value="Some comment">
                    </div>
                </div>
                </div>';

                $output .= '</div>
                <input type="text" class="form-control" placeholder="Comment">
                <div class="container">
                <button type="submit btn-primary">Post</button>
                </div>
                </form>
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