<?php
require_once __DIR__.'/vendor/autoload.php';
session_start();
$client = new Google_Client();
$client->setAuthConfig('jsonmail.json');
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$client->setAccessToken($_SESSION['access_token']);
	$service = new Google_Service_Gmail($client);
	// Print the labels in the user's account.
	$user = 'me';
	$messageId = "1612f09cd2f786d9";
	$message = getMessage($service,$user,$messageId);
	echo "<pre>";
	print_r($message["snippet"]);
	 
 
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/1-proje/mail/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function getMessage($service, $userId, $messageId) {
  try {
    $message = $service->users_messages->get($userId, $messageId);
    print 'Message with ID: ' . $message->getId() . ' retrieved.';
    return $message;
  } catch (Exception $e) {
    print 'An error occurred: ' . $e->getMessage();
  }
} 