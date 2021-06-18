<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location: /login.php");
}else{
    if($_SESSION['user']['is_admin'] != 1){
        header("location: /user.php");
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Expression | Admin Panel</title>
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


  <body id="body">

    <!-- Navbar -->
    <nav id="mainNavbar" class="scrolled navbar navbar-dark navbar-expand-md py-0 px-5 fixed-top justify-content-around">
        <a href="index.php" class="navbar-brand"><img class="d-none d-lg-inline col-md-1"
                src="" alt="">EXPRS</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggl navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navLinks">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Log Out</a>
                </li>
           
            </ul>
        </div>
    </nav>

    <div class="container">
        <table class="table table-hover table-striped table-dark mt-5 justify-content-center">
            <thead>
                <tr class="display-5">
                    <th>Username</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody class="admin-users-area">
                <tr>
                    <td scope="row">asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td><div class="dropdown open">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Dropdown
                                </button>
                        <div class="dropdown-menu" aria-labelledby="triggerId">
                            <button class="dropdown-item" href="#">Disable Account</button>
                            <button class="dropdown-item" href="#">Reinstate Account</button>
                            <button class="dropdown-item" href="#">Delete Account</button>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Link to profile</a>
                        </div>
                    </div></td>
                </tr>
                <tr>
                    <td scope="row">asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td><div class="dropdown open">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Dropdown
                                </button>
                        <div class="dropdown-menu" aria-labelledby="triggerId">
                            <button class="dropdown-item" href="#">Disable Account</button>
                            <button class="dropdown-item" href="#">Reinstate Account</button>
                            <button class="dropdown-item" href="#">Delete Account</button>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Link to profile</a>
                        </div>
                    </div></td>
                </tr>
            </tbody>
        </table>
    </div>



    
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="admin/admin.js"></script>
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
