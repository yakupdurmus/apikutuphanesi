<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		#desc{
			width: 80%;
			height: auto;
			margin: 5px;
			border: 1px solid #ddd;
			padding: 5px;
			border-radius: 0px 0px 10px 10px;
			box-shadow: 2px 2px #888888;

		}
		#video{
			width: 80%;
			height: auto;;
		}
		iframe{
			width:560px;
			height:315px;
			position: relative;
    		margin: auto;
		}
		 
		#baslik{
			border-radius: 10px 10px 0px 0px;
			width: 80%;
			height: auto;
			margin: 5px;
			border: 1px solid #ddd;
			padding: 5px;
			margin-top: 10px;
			background: #eee;
		}
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

    ?>
    <div id=video>
    	<iframe width="560" height="315" src="https://www.youtube.com/embed/r3uOBb3BM-0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
    <?php
	$response = commentThreadsListByVideoId($service,
    'snippet,replies', 
    array('videoId' => 'r3uOBb3BM-0'));
	$response = $response["items"];
	 
	foreach ($response as $key => $value) {
		$deg = $value["snippet"]["topLevelComment"]["snippet"];
		echo '<div id="baslik"><img src="'.$deg["authorProfileImageUrl"].'"> Ad :'.$deg["authorDisplayName"].'</div>';
		echo '<div id="desc"><p>Yazı :'.$deg["textDisplay"].' <br><br>Like : '.$deg["likeCount"].'  Yayım Tarihi : '.$deg["publishedAt"].'</p></div>';
	}
 
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function commentThreadsListByVideoId($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->commentThreads->listCommentThreads(
        $part,
        $params
    );
    return $response;
}

?>
</body>
</html>