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
<div id="loader">
    <div id="loading" style="position: fixed;background-color: black"></div>
</div>

<!-- Navbar -->
<nav id="mainNavbar"
     class="scrolled navbar navbar-dark navbar-expand-md py-0 px-5 fixed-top justify-content-around">
    <a href="index.php" class="navbar-brand"><img class="d-none d-lg-inline col-md-1" src="" alt="">EXPRS</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end mx-1" id="navLinks">
        <form class="form-inline my-2 my-lg-0 post-search-form">
            <input class="form-control mr-sm-2" type="text" id="post-search-tag" name="tag" placeholder="#"
                   aria-label="Search">
            <button type="button" class="btn btn-outline-success my-2 my-sm-0 custom" id="post-search-button">Go
            </button>
        </form>
        <!-- Trigger NEW POST modal -->
        <button type="button" class="btn btn-primary btn-sm custom btn-create" data-toggle="modal"
                data-target="#modelId">
            Create a New Post
        </button>
        <!-- Trigger CHAT modal-->
        <button type="button" class="btn btn-outline-info btn-sm custom" data-toggle="modal" data-target="#modelChat">
            Messages
        </button>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Log Out</a>
            </li>
        </ul>
    </div>
</nav>

<!--Chat Notification-->
<div role="alert" aria-live="assertive" aria-atomic="true" class="toast position-fixed" data-autohide="false"
     style="margin-top: 60px; margin-left: 10px;">
    <div class="toast-header">
        <svg class=" rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
            <rect fill="#2972b6" width="100%" height="100%"/>
        </svg>
        <strong class="mr-auto">EXPRS</strong>
        <small></small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body" id="notification-body">
    </div>
</div>


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
<div class="success hide Fixed">
    <span class="msg">You successfully created a post!</span>
    <span class="croise"><i class="fa fa-times"></i></span>
</div>

<br>
<div class="container mt-5 post-card-area col-md-4">
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
                                <input type="text" class="form-control2" name="tags" id="tags" aria-describedby="helpId"
                                       placeholder="">
                                <small id="helpId" class="form-text text-muted">Make sure to include a #</small>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary alert-btn" data-dismiss="modal" id="post">Post</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CHAT MODAL -->
    <div class="modal fade" id="modelChat" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!--                    <h5 class="modal-title">@username</h5>-->
                    <form action="#" id="chat-search-form" autocomplete="off">
                        <div class="row">
                            <div class="col-sm-1"><h5>@</h5></div>
                            <div class="col-sm-8"><input type="text" class="form-control" name="chat-receiver-username"
                                                         id="chat_receiver_username" placeholder="username"></div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-primary" id="chat-search" name="chat-search">Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <button type="button" class="close" id="close-chat" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="overflow-auto chat-box scroll" style="height: 40vh; overflow-y: auto;">
                    </div>
                    <div class="container-fluid">
                        <form class="chat-typing-area" autocomplete="off">
                            <input type="hidden" name="sender_id" id="sender_id"
                                   value="<?php echo $_SESSION['user']['uid']; ?>">
                            <input type="hidden" name="receiver_id" id="receiver_id" value="">
                            <input type="hidden" id="message_ids" name="message_ids" value="">
                            <input type="hidden" name="read_messages" id="read_messages" value="">
                            <div class="form-group">
                                <label for="usermessage">Message: </label>
                                <input type="text" class="form-control chat-input-field" name="usermessage"
                                       id="usermessage"
                                       placeholder="Type Here">
                                <button type="button" class="btn btn-primary chat-send" id="chat-send">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="modalConfirmation" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this post...</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <form class="delPost" action="delete_post.php" method="post">
                        <button type="submit" class="btn btn-danger btn-ok">Delete</button>
                        <input type="hidden" name="post_to_delete" id="post_to_delete" value="">
                        <input type="hidden" name="post_to_delete_uid" id="post_to_delete_uid" value="">
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="scripts/create-post.js"></script>
<script type="text/javascript" src="scripts/list-posts.js"></script>
<script type="text/javascript" src="scripts/comment.js"></script>
<script type="text/javascript" src="scripts/chat.js"></script>
<script type="text/javascript" src="scripts/notification.js"></script>
<script>
    document.getElementById

    function sendComment(id) {
        var form = document.getElementById('comment_form_' + id);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "scripts/comment.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    if (data == "empty") {
                        console.log("empty message");
                    } else if (data == "nosesh") {
                    } else {
                    }
                }
            }
        }
        let formData = new FormData(form);
        xhr.send(formData);
    }
</script>
<script>
    $(function () {
        $(document).scroll(function () {
            var $nav = $("#mainNavbar");
            $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height())
        });
    });
</script>
<script>
    $('#post').click(function () {
        $('.success').addClass("show");
        $('.success').addClass("alert");
        $('.success').removeClass("hide");
        setTimeout(function () {
            $('.success').removeClass("show");
            $('.success').addClass("hide");
        }, 5000)
    });
    $('.croise').click(function () {
        $('.success').removeClass("show");
        $('.success').addClass("hide");
    });
</script>
<script>
    function hideLoader() {
        $('#loading').fadeOut(800);
        clearInterval(loading_screen_interval);
    }

    var loading_screen_interval = setInterval(() => {
        var unloaded_elements = document.getElementsByClassName("unloaded");
        if (unloaded_elements.length == 0) {
            hideLoader();
            //setTimeout(hideLoader, 1 * 1000);
        }
    }, 1500)
</script>
<script>
    function postDelConfirmation(id,uid) {
        $("#post_to_delete").val(id);
        $("#post_to_delete_uid").val(uid);
        $("#modalConfirmation").modal();
    }
</script>
</body>

</html>