<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location: /login.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>A Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/388b02779c.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400;700&display=swap" rel="stylesheet">

</head>


<body class="user">

    <!-- Navbar -->
    <nav id="mainNavbar"
        class="scrolled navbar navbar-dark navbar-expand-md py-0 px-5 fixed-top justify-content-around">
        <a href="index.php" class="navbar-brand"><img class="d-none d-lg-inline col-md-1" src="" alt="">EXPRS</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggl navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navLinks">
        <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="#" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Go</button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Header  -->

    <!-- <header>
        <div class="overlay"></div>
        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="videos/art.mp4" type="video/mp4">
        </video>
        <div class="container h-100">
            <div class="d-flex h-100 text-center justify-content-center align-items-center">
            </div>
        </div>
    </header> 
    </div> -->

    <!-- USER CARDS -->
    
    <div class="container mt-5">
        <div class="card-deck">
            <div class="card">
                <img class="card-img-top" src="..." alt="Card image cap">
                <div class="card-body">
                    <h2 class="card-title">Card title</h>
                    <p class="card-text display-3">This is a wider card with supporting text below as a natural lead-in to additional
                        content. This content is a little bit longer.</p>
                    <form>
                        <div class="form-group row">
                        <label for="readcomments" class="col-form-label"><h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit molestias obcaecati voluptatibus optio dignissimos enim autem tenetur nisi. Molestiae et dolorem voluptatibus voluptatem corporis maxime quasi, ullam aliquid cum harum dolorum sapiente laudantium inventore dolor consectetur facilis fugit, officia, nobis saepe tempora quas ea autem sed. Sapiente ratione voluptas dolorum.</h3></label>
                        <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="readcomments" value="Some comment">
                        </div>

                    </div>
                    </form>
                </div>
                <input type="text" class="form-control" placeholder = "Comment">
                <div class="container">
                <button type="submit btn-primary">Post</button></div>
                <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                </div>
            </div>
            <div class="card">
                <img class="card-img-top" src="..." alt="Card image cap">
                <div class="card-body">
                    <h3 class="card-title">Card title<h3>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                </div>
            </div>
            <div class="card">
                <img class="card-img-top" src="..." alt="Card image cap">
                <div class="card-body">
                    <h2 class="card-title">Card title</h3>
                    <p class="card-text">This is a wider card with supporting text below as a nn.</p>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                </div>

            </div>
        </div>

        
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script>
        $(function () {
            $(document).scroll(function () {
                var $nav = $("#mainNavbar");
                $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height())
            });
        });

    </script>
</body>

</html>