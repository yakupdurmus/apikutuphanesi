<?php

  session_start();

  function dst()
  {
    session_destroy();
  }

  include ('lib/ins.class.php');
  include ('lib/ins.conf.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>
    <meta http-equiv="content-language" content="tr-TR" />
    <title>Instagram Giriş Sayfası</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<?php

if(empty($_SESSION['userdetails']->user->username) || empty($_SESSION['instagramdata']))
{
        // sınıf içinde instagram izin sayfasının linkini hazırlayan fonksiyon
        $instagramLoginUrl = $instagram->getLoginUrl();
        // izin sayfasının linki
       echo "Link :".$instagramLoginUrl."<br><br>";
       echo "<a href='".$instagramLoginUrl."'>Instagram hesabınızla giriş yapın</a>";

}else
{
  header("location:islem.php");
}
?>
</body>
</html>