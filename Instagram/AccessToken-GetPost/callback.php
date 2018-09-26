<?php
include ('lib/ins.class.php');
include ('lib/ins.conf.php');

$code = $_GET['code'];
if (true === isset($code)) {

	$data = $instagram->getOAuthToken($code);
	$instagram->setAccessToken($data);
	if(empty($data->user->username))
	{
		header('Location: index.php');
	}
	else
	{
		session_start();
		$_SESSION['userdetails']=$data;
		$_SESSION['instagramdata']= $instagram;
		header('Location: islem.php');
	}
} 
else 
{
	if (true === isset($_GET['error'])) 
	{
	    echo 'Bir hata oluştu: '.$_GET['error_description'];
	}

}
?>