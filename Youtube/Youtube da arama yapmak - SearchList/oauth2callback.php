<?php
require_once __DIR__.'/vendor/autoload.php';
session_start();
$client = new Google_Client();
$client->setAuthConfigFile('jsonyoutube.json');
$client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
$client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/oauth2callback.php');

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/youtube/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}