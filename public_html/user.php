<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: /login.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Expression</title>
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
    <div class="collapse navbar-collapse justify-content-end mx-1" id="navLinks">
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="#" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Go</button>
        </form>
        <!-- Trigger NEW POST modal -->
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modelId">
            Create a New Post
        </button>
            <!-- Trigger CHAT modal-->
        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#modelChat">
            Messages
        </button>
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
<br>
<div class="container mt-5 post-card-area">
</div>

<div class="container mt-5">


    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Creating a New Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="#" class="create-post" autocomplete="off">
                            <div class="form-group">
                                <label for="title">Title: </label>
                                <input type="text" class="form-control-file" name="title" id="title" placeholder="">
                                <small id="fileHelpId" class="form-text text-muted">Add a title</small>
                            </div>
                            <div class="form-group">
                                <label for="upload">Upload: </label>
                                <input type="file" class="form-control-file" name="upload" id="upload" placeholder=""
                                       aria-describedby="fileHelpId">
                                <small id="fileHelpId" class="form-text text-muted">Add an image</small>
                            </div>
                            <div class="form-group">
                                <label for="">Message: </label>
                                <textarea name="message" id="message" cols="40" rows="4"></textarea>
                                <small id="helpId" class="form-text text-muted">Say something</small>
                            </div>
                            <div class="form-group">
                                <label for="">Tags: </label>
                                <input type="text" class="form-control" name="tags" id="tags" aria-describedby="helpId"
                                       placeholder="">
                                <small id="helpId" class="form-text text-muted">Make sure to include a #</small>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="post">Post</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CHAT MODAL -->
    <div class="modal fade" id="modelChat" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@username</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container d-flex justify-content center">
                    <div class="d-flex flex-row p-3 col-md-12" id="friendMessage"> <i class="fas fa-user-friends"></i>
                    <div class="chat ml-2 p-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum, maxime? Eum saepe velit eius magni, quos accusantium similique deleniti illum. Ad vel odio dolorem illum voluptatibus in fugit debitis recusandae.</div>
                    </div>
                </div>
                <div class="d-flex flex-row p-3">
                        <div class="bg-white mr-2 p-3" id="userMessage"><span class="text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quod, eveniet laudantium repudiandae unde dignissimos officiis, ea nulla atque vitae numquam enim! Deserunt dolores harum eveniet?</span></div> <i class="fas fa-user-friends"></i>
                     </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="#" class="usermessage" autocomplete="off">
                            <div class="form-group">
                                <label for="usermessage">Message: </label>
                                <input type="text" class="form-control" name="usermessage" id="usermessage" placeholder="Type Here">
                                <button type="button" class="btn btn-primary" id="send">Send</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    <script>-->
<!--        $('#exampleModal').on('show.bs.modal', event => {-->
<!--            var button = $(event.relatedTarget);-->
<!--            var modal = $(this);-->
<!--            // Use above variables to manipulate the DOM-->
<!---->
<!--        });-->
<!--    </script>-->


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
<script type="text/javascript" src="scripts/create-post.js"></script>
<script type="text/javascript" src="scripts/list-posts.js"></script>
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