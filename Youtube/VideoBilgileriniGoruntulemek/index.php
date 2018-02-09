<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	body{
			font-family: Arial, Helvetica, sans-serif;
	}
	 
	 
	.kutu{
		width: 60%;
		float: left;
		margin: 5px;
		padding: 10px;
		padding-left: 10px;
		border-radius: 3px;
		border:1px solid #ddd;

	 
	</style>
	<script src="https://apis.google.com/js/platform.js"></script>
</head>
<body>



<?php
require_once __DIR__.'/vendor/autoload.php';
session_start();
$client = new Google_Client();
$client->setAuthConfig('jsonyoutube.json');
$client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
$client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$client->setAccessToken($_SESSION['access_token']);
	$service = new Google_Service_YouTube($client);
 	
 	$response  = videosListById($service,'snippet,statistics', array('id' => 'jVsUOiBzu7o'));
 	
 	$bilgi = $response["items"][0]["snippet"];
 	$goruntu = $response["items"][0]["statistics"];

 	echo "<div class='kutu'>Video Başlık :".$bilgi["title"]."</div>";
 	echo "<div class='kutu'><img src=".$bilgi['thumbnails']['high']['url'].">"."</div>";
 	echo "<div class='kutu'>Kanal Adı :".$bilgi["channelTitle"]."</div>";
 	echo "<div class='kutu'>Açıklama :".$bilgi["description"]."</div>";
 	echo "<div class='kutu'>Taglar : ";
 	foreach ($bilgi["tags"] as $key => $value) {
 		echo $value.",";
 	}
 	echo "</div><div class='kutu'>Video Yüklenme Tarihi : ".$bilgi["publishedAt"]."</div>";
 	echo "<div class='kutu'>Yorum Sayısı : ".$goruntu["commentCount"]."</div>";
 	echo "<div class='kutu'>Dislike : ".$goruntu["dislikeCount"]."</div>";
 	echo "<div class='kutu'>Like : ".$goruntu["likeCount"]."</div>";
 	echo "<div class='kutu'>Görüntülenme : ".$goruntu["viewCount"]."</div>";
 
 
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function videosListById($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->videos->listVideos(
        $part,
        $params
    );
    return $response;
}

?>
</body>
</html>