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
if (!isset($_GET['tags'])) {
    echo "no_tags";
} else {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    $hashtags = $_GET['tags'];
    $data = json_encode(array("hashtags" => $hashtags));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_URL, 'http://18.207.233.207/api/spotify-genre-recommendation');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($curl);
    $result = json_decode($data, true);
    curl_close($curl);
    header("Content-Type: application/json");
    echo $data;

//    $genres = explode(",", $result['recommendation']['genres']);
//
//    echo "<p style='margin-left: 12px;'>Top 5 genre recommendations for the following hashtags (" . $hashtags . ")</p>";
//    foreach ($genres as &$genre) {
//        echo "<p style='margin-left: 12px;'>" . $genre . "</p>";
//    }
//            $seed_genre = implode("%2C", $genres);
//
//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/recommendations?limit=10&seed_genres=' . $seed_genre);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//                'Accept: application/json',
//                'Content-Type: application/json',
//                'Authorization: Bearer ' . $access_token
//            ));
//            $recommendations = json_decode(curl_exec($curl), true);
//            curl_close($curl);
//            $tracks = $recommendations['tracks'];
//            echo "<p style='margin-left: 12px;'>Top 10 song recommendations for the following hashtags (" . $hashtags . ")</p>";
//            foreach ($tracks as &$track) {
//                echo "<a style='margin-left: 12px;' href='" . $track['external_urls']['spotify'] . "'>" . $track['name'] . "</a><br>";
//                $track_uri = explode(":", $track['uri']);
//
//                echo '<iframe src="https://open.spotify.com/embed/' . $track_uri[1] . "/" . $track_uri[2] . '" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe><br><br>';
//            }
}



//curl_setopt($spotify_curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Accept: application/json","Authorization: Bearer"));
?>