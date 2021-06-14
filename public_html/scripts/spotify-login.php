<?php
session_start();
if(isset($_SESSION['user']['refresh_code'])){
    header("Location: /user.php");
}else{
    if (!isset($_SESSION['user']['code'])) {
        if (!isset($_GET['code'])) {
            $auth_query = array(
                'client_id' => getenv("SPOTIFY_CLIENT_ID"),
                'redirect_uri' => 'https://socialnetworking490-dev.herokuapp.com/scripts/spotify-login.php',
                'response_type' => 'code'
            );
            $auth_url = 'https://accounts.spotify.com/authorize?' . http_build_query($auth_query);
            header('Location: ' . $auth_url);
        } else {
            $_SESSION['user']['code'] = $_GET['code'];
            header('Location: spotify-login.php');
        }
    } else {
        if (!isset($_SESSION['user']['refresh_code'])) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            $data = array("grant_type" => 'authorization_code',
                "code" => $_SESSION['user']['code'],
                "redirect_uri" => "https://socialnetworking490-dev.herokuapp.com/scripts/spotify-login.php");
            $post_fields = http_build_query($data);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode(getenv("SPOTIFY_CLIENT_ID") . ":" . getenv("SPOTIFY_CLIENT_SECRET"))));
            curl_setopt($curl, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);
            $_SESSION['user']['refresh_code'] = $result['refresh_token'];
            header('Location: /user.php');
        }
    }
}
