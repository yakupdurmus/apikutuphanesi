<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	
* {
    box-sizing: border-box;
}
body{
            font-family: Arial, Helvetica, sans-serif;
}
     
input[type=text], select, textarea{
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    resize: vertical;
}

label {
    padding: 12px 12px 12px 0;
    display: inline-block;
}

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: right;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.container {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
}

.col-25 {
    float: left;
    width: 25%;
    margin-top: 6px;
}

.col-75 {
    float: left;
    width: 75%;
    margin-top: 6px;
}

.row:after {
    content: "";
    display: table;
    clear: both;
}

@media (max-width: 600px) {
    .col-25, .col-75, input[type=submit] {
        width: 100%;
        margin-top: 0;
    }
}
</style>
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
 	
    if (!$_POST && !$_FILES) {
       ?>
<h2>Youtube Dosya Upload Etmek</h2>
 

<div class="container">
  <form action="#" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-25">
        <label for="fname">Video Başlığı</label>
      </div>
      <div class="col-75">
        <input type="text" id="fname" name="baslik" placeholder="Video Başlığı..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="lname">Video Açıklama</label>
      </div>
       <div class="col-75">
        <textarea id="subject" name="aciklama" placeholder="Video Açıklama.." style="height:200px"></textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="lname">Taglar (birden fazla tagı virgül ile ayır)</label>
      </div>
      <div class="col-75">
        <input type="text" id="lname" name="tag" placeholder="Video Taglar..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="country">Görünürlük</label>
      </div>
      <div class="col-75">
        <select id="country" name="gorunurluk">
          <option value="public">public</option>
          <option value="private">private</option>
        </select>
      </div>
      <div class="row">
      <div class="col-25">
        <label for="fname">Videoyu seçiniz</label>
      </div>
      <div class="col-75">
         <input type="file" name="video" id="fileToUpload">
      </div>
    </div>
    </div>
    <div class="row">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>
       <?php
    }else
    {

print_r($_FILES["video"]);
echo "Video Yükleniyor.";
$media_file =$_FILES["video"]["tmp_name"];
        videosInsert($client,
            $service,
            $media_file,
            array('snippet.categoryId' => '22',
                   'snippet.defaultLanguage' => '',
                   'snippet.description' => $_POST["aciklama"],
                   'snippet.tags[]' => $_POST["tag"],
                   'snippet.title' => $_POST["baslik"],
                   'status.embeddable' => '',
                   'status.license' => '',
                   'status.privacyStatus' => $_POST["gorunurluk"],
                   'status.publicStatsViewable' => ''),
            'snippet,status', array()); 
        echo "<br>Video Yüklendi!";
 
    }
 
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

 function videosInsert($client, $service, $media_file, $properties, $part, $params) {
    $params = array_filter($params);
    $propertyObject = createResource($properties); // See full sample for function
    $resource = new Google_Service_YouTube_Video($propertyObject);
    $client->setDefer(true);
    $request = $service->videos->insert($part, $resource, $params);
    $client->setDefer(false);
    $response = uploadMedia($client, $request, $media_file, 'video/*');
    print_r($response);
}
function createResource($properties) {
    $resource = array();
    foreach ($properties as $prop => $value) {
        if ($value) {
            addPropertyToResource($resource, $prop, $value);
        }
    }
    return $resource;
}
function addPropertyToResource(&$ref, $property, $value) {
    $keys = explode(".", $property);
    $is_array = false;
    foreach ($keys as $key) {
        if (substr($key, -2) == "[]") {
            $key = substr($key, 0, -2);
            $is_array = true;
        }
        $ref = &$ref[$key];
    }

    if ($is_array && $value) {
        $ref = $value;
        $ref = explode(",", $value);
    } elseif ($is_array) {
        $ref = array();
    } else {
        $ref = $value;
    }
}
function uploadMedia($client, $request, $filePath, $mimeType) {
    $chunkSizeBytes = 1 * 1024 * 1024;

    $media = new Google_Http_MediaFileUpload(
        $client,
        $request,
        $mimeType,
        null,
        true,
        $chunkSizeBytes
    );
    $media->setFileSize(filesize($filePath));

    $status = false;
    $handle = fopen($filePath, "rb");
    while (!$status && !feof($handle)) {
      $chunk = fread($handle, $chunkSizeBytes);
      $status = $media->nextChunk($chunk);
    }

    fclose($handle);
    return $status;
}

?>
</body>
</html>