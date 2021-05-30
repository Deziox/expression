<?php
/**
 * @var array $errors
 * @var string $username
 * @var string $email
 */
    include('../login/register_handler.php');
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
                    <a href="login.php" class="nav-link">Log In</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Form -->
    <section class="container pt-5 justify-content-center mt-5">
        <div class="jumbotron pt-5">
            <div class="align-self-center mb-2">Enter your information:</div>

            <form action="register.php" method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="Username">Username</label>
                        <?php echo "<div class=\"error\">".$errors['username']."</div>";?>
                        <input type="text" class="form-control" id="username" placeholder="John" <?php echo 'value="'.$username.'"'?> name="username">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="Email">E-mail</label>
                        <?php echo "<div class=\"error\">".$errors['email']."</div>";?>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" <?php echo 'value="'.$email.'"'?>name="email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <?php echo "<div class=\"error\">".$errors['password']."</div>";?>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="confirmpassword">Confirm Password</label>
                        <?php echo "<div class=\"error\">".$errors['confirm']."</div>";?>
                        <input type="password" class="form-control" id="password" placeholder="confirm password" name="confirm-password">
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-primary" value="register">Submit</button>
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