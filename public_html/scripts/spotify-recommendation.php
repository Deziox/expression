<?php
/*
 * Danzel Serrano:
 *      In order to recommend a song with respect to
 *      a post's hashtags, we had to create a separate
 *      AWS EC2 server to host a REST API in python Flask.
 *      This was done to refrain from loading Word2Vec on
 *      each page load (gensim).
 *
 *      With this REST API we send a list of words
 *      to calculate the cosine similarities with
 *      each of Spotify's genre categories (i.e. 'jazz', 'piano').
 *      (This was done with pretrained Word2Vec vectors.)
 */
session_start();
if(!isset($_GET['tags'])){
    echo "no_tags";
}else {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
    } else {
        if (!isset($_SESSION['user']['code'])) {
            if (!isset($_GET['code'])) {
                $auth_query = array(
                    'client_id' => '6caca287eab0474eacb78c98af31e491',
                    'redirect_uri' => 'https://socialnetworking490-dev.herokuapp.com/scripts/spotify-recommendation.php',
                    'response_type' => 'code'
                );
                $auth_url = 'https://accounts.spotify.com/authorize?' . http_build_query($auth_query);
                echo $auth_url;
                header('Location: ' . $auth_url);
            } else {
                $_SESSION['user']['code'] = $_GET['code'];
                header('Location: spotify-recommendation.php');
            }
        } else {

            $access_token = "access token";
            if (!isset($_SESSION['user']['refresh_code'])) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                $data = array("grant_type" => 'authorization_code',
                    "code" => $_SESSION['user']['code'],
                    "redirect_uri" => "https://socialnetworking490-dev.herokuapp.com/scripts/spotify-recommendation.php");
                $post_fields = http_build_query($data);

                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode(getenv("SPOTIFY_CLIENT_ID") . ":" . getenv("SPOTIFY_CLIENT_SECRET"))));
                curl_setopt($curl, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                $result = json_decode(curl_exec($curl), true);
                curl_close($curl);
                $_SESSION['user']['refresh_code'] = $result['refresh_token'];
                $access_token = $result['access_token'];
            } else {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                $data = array("grant_type" => 'refresh_token',
                    "refresh_token" => $_SESSION['user']['refresh_code']);
                $post_fields = http_build_query($data);

                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode(getenv("SPOTIFY_CLIENT_ID") . ":" . getenv("SPOTIFY_CLIENT_SECRET"))));
                curl_setopt($curl, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                $result = json_decode(curl_exec($curl), true);
                curl_close($curl);
                $access_token = $result['access_token'];
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            $hashtags = $_GET['tags'];
            $data = json_encode(array("hashtags" => $hashtags));

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_URL, 'http://18.207.233.207/api/spotify-genre-recommendation');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);
            $genres = explode(",", $result['recommendation']['genres']);

            echo "<p style='margin-left: 12px;'>Top 5 genre recommendations for the following hashtags (" . $hashtags . ")</p>";
            foreach ($genres as &$genre) {
                echo "<p style='margin-left: 12px;'>" . $genre . "</p>";
            }
            $seed_genre = implode("%2C", $genres);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/recommendations?limit=10&seed_genres=' . $seed_genre);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ));
            $recommendations = json_decode(curl_exec($curl), true);
            curl_close($curl);
            $tracks = $recommendations['tracks'];
            echo "<p style='margin-left: 12px;'>Top 10 song recommendations for the following hashtags (" . $hashtags . ")</p>";
            foreach ($tracks as &$track) {
                echo "<a style='margin-left: 12px;' href='" . $track['external_urls']['spotify'] . "'>" . $track['name'] . "</a><br>";
                $track_uri = explode(":", $track['uri']);

                echo '<iframe src="https://open.spotify.com/embed/' . $track_uri[1] . "/" . $track_uri[2] . '" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe><br><br>';
            }
        }
    }
}


//curl_setopt($spotify_curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Accept: application/json","Authorization: Bearer"));
?>
<!doctype html>
<html lang="en">
<head>
    <title>Expression | Api</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/388b02779c.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../stylesheets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400;700&display=swap" rel="stylesheet">

</head>


<body id="body">
<!--<script src="https://sdk.scdn.co/spotify-player.js"></script>-->

<div class="container">

</div>





<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!--<script>-->
<!--    $(function () {-->
<!--        $(document).scroll(function () {-->
<!--            var $nav = $("#mainNavbar");-->
<!--            $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height())-->
<!--        });-->
<!--    });-->
<!---->
<!--</script>-->
</body>
</html>
