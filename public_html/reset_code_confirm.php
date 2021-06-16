<?php
/**
 * @var array $errors
 * @var string $cleardb_server
 * @var string $cleardb_username
 * @var string $cleardb_password
 * @var string $cleardb_db
 */
session_start();
if(!isset($_GET['uid'])){
    header("location: login_success.php");
}
if(isset($_SESSION['user'])){
    header("location: login_success.php");
}

$errors = array('code'=>'');

require('../config.php');

try{
    $db = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db",$cleardb_username,$cleardb_password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //Check if request expired or exists
    $stmt = $db->prepare("SELECT * FROM forgot_requests WHERE userid = :uid AND end_time > NOW()");
    $stmt->execute(array(":uid" => $_GET['uid']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$result){
        header("location: index.php");
    }else{
        if(isset($_POST['submit'])){
            if(empty($_POST['code'])) {
                $errors['code'] = "Reset code cannot be empty";
            }
            if(!array_filter($errors)){
                if(password_verify($_POST['code'],$result['reset_code'])){
                    $_SESSION['rcode'] = $_POST['code'];
                    header("location: reset_password.php?uid=".$_GET['uid']);
                }else{
                    $errors['code'] = "Incorrect reset code";
                }
            }
        }
    }

}catch(PDOException $e){
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheets/style.css">
</head>
<body>

<div class="container">
    <!-- navbar -->
    <nav id="regNavbar" class="navbar navbar-dark navbar-expand-md py-0 px-5 fixed-top justify-content-around">
        <a href="index.php" class="navbar-brand"><img class="d-none d-lg-inline col-lg-1" src="imgs/goldensquarelogo.png" alt="">CS490</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggl navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navLinks">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="register.php" class="nav-link">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Form -->
    <section class="container pt-5 justify-content-center mt-5">
        <div class="jumbotron pt-5">
            <div class="align-self-center mb-2">Enter the code you received in your email to reset your password</div>

            <?php echo '<form action="reset_code_confirm.php?uid='.$_GET['uid'].'" method="post">'?>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="code">Reset Code</label>
                        <?php echo "<div class=\"error\">".$errors['code']."</div>";?>
                        <input type="text" class="form-control" id="code" placeholder="Enter reset code" name="code">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Submit</button>

        </div>


</div>


</form>

</div>
</section>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>


