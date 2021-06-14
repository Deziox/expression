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

function get_recommendation($tags)
{
    if (empty($tags)) {
        return "no_tags";
    } else {
        if (!isset($_SESSION['user'])) {
            header('Location: /login.php');
        } else {
            $ret_val = "";
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

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            $hashtags = $tags;
            $data = json_encode(array("hashtags" => $hashtags));

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_URL, 'http://18.207.233.207/api/spotify-genre-recommendation');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);
            $genres = explode(",", $result['recommendation']['genres']);

//            echo "<p style='margin-left: 12px;'>Top 5 genre recommendations for the following hashtags (" . $hashtags . ")</p>";
//            foreach ($genres as &$genre) {
//                echo "<p style='margin-left: 12px;'>" . $genre . "</p>";
//            }
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
            $rec = $tracks[array_rand($tracks)[0]];
            $track_uri = explode(":", $rec['uri']);
            $ret_val .= '<iframe src="https://open.spotify.com/embed/' . $track_uri[1] . "/" . $track_uri[2] . '" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe><br><br>';
            return $ret_val;
        }
    }
}
?>