<?php
require_once __DIR__.'/vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setAuthConfigFile('jsonmail.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/1-proje/mail/oauth2callback.php');
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/mail/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}