<?php
/**
 * @var Aws\S3\S3Client $s3
 * @var string $bucket
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */

session_start();
if(!isset($_SESSION['user'])){
    header("location: /login.php");
}else if($_SESSION['user']['is_admin'] != 1){
    header("location: /user.php");
}else{
    include_once "../config.php";
    $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $output = "";
    while($result){
        $status = '<td style="color: #9BFF6E;text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">enabled</td>';
        if($result['disabled']){
            $status = '<td style="color: #FF796E;text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">disabled</td>';
        }
        $output .= '<tr>
                    <td scope="row">'.$result['username'].'</td>
                    <td>'.$result['email'].'</td>'.$status
                    .'<td><div class="dropdown open">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Dropdown
                                </button>
                        <div class="dropdown-menu" aria-labelledby="triggerId">
                            <button class="dropdown-item" onclick="disable_account('.$result['userid'].')">Disable Account</button>
                            <button class="dropdown-item" onclick="reinstate_account('.$result['userid'].')">Reinstate Account</button>
                            <button class="dropdown-item" href="#">Delete Account</button>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Link to profile</a>
                        </div>
                    </div></td>
                </tr>';
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    echo $output;
}
