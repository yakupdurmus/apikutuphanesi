<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	body{
			font-family: Arial, Helvetica, sans-serif;
	}
	b{
		color: #2c3e50;
	}
	 
	.kutu{
		width: 60%;
		float: left;
		margin: 5px;
		padding: 10px;
		padding-left: 10px;
		border-radius: 3px;
		border:1px solid #ddd;

	}
	.kutu:hover{
		background-color: #efefef;
	}
	.baslik{
		padding: 10px 0;
	}
	.yazi{
		width: 778px;
		float: left;
	}
	.kutu:hover .baslik b{
		color: #000;
		text-decoration: underline;

	}
	.kutu .resim{
		width: 170px;
		height: auto;
		float: left;
		margin: 5px;
		border-radius: 3px;
	}
	.kutu .yazi{
		height: auto;
		float: left;
		margin: 5px;
		padding: 0 10px;

	}
	.vbaslik{
		width: 100%;
		float: left;
	}
	.vyazi{
		margin-top: 20px;
		width: 100%;
		float: left;
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
	<form action="#" method="get" class="kutu">
		Youtube Search : <input type="text" name="ara">
		<input type="submit" name="gonder">
	</form> 
	<?php

 	if(isset($_GET['ara'])) {

	$response = searchListByKeyword($service,'snippet', array('maxResults' => 25, 'q' => $_GET['ara'], 'type' => ''));
	$response = $response["items"];
	echo "<pre>";
	print_r($response);
	die();
	echo "<div class='kutu'>Aranan Kelime : ".$_GET['ara']."<br></div>";

	foreach ($response as $key => $value) {
			$deg = $value["snippet"];
			?>
			
			<div class="kutu">
				<div class="baslik"> <b><?=$deg["title"]?></b> </div>
				<div>
					<img class="resim" src="<?=$deg['thumbnails']['medium']['url']?>">
					<div class="yazi">
						<span  class="vbaslik">Selçuk üniversitesi bilgisayar mühendisliği</span>
						<span class="vyazi">
							<b>Açıklama :</b><?=$deg["description"]?><br>
							<b>Kanal Başlık :</b><?=$deg["channelTitle"]?><br>
							<b>Yayım tarih :</b><?=$deg["publishedAt"]?>
						</span>
					</div>
				</div>
			</div>

			<?php
		}	
	}
	die();
	 
	foreach ($response as $key => $value) {
		$deg = $value["snippet"]["topLevelComment"]["snippet"];
		echo '<div id="baslik"><img src="'.$deg["authorProfileImageUrl"].'"> Ad :'.$deg["authorDisplayName"].'</div>';
		echo '<div id="desc"><p>Yazı :'.$deg["textDisplay"].' <br><br>Like : '.$deg["likeCount"].'  Yayım Tarihi : '.$deg["publishedAt"].'</p></div>';
	}
 
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function searchListByKeyword($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->search->listSearch(
        $part,
        $params
    );

  	return $response;
}



?>
</body>
</html>