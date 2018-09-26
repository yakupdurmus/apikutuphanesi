<?php

include ('lib/ins.class.php');
include ('lib/ins.conf.php');

ob_start();
session_start();

$userData = $_SESSION['userdetails'];
$instagram = $_SESSION['instagramdata'];

 if (!isset($userData) || is_null($userData)) 
 {
     header("Location :index.php");
 }

?>
 
<head>
    <meta http-equiv="content-language" content="tr-TR" />
    <title>Apikütüphanesi.com - İnstagram Api kullanımı </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        .kutu{
            width: 700px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
        .resim{
            width: 320px;
            margin:3 auto;

        }
        .icerik{
            width: 100%;
            border-bottom: 1px solid #eee;
            margin-bottom: 3px;
        }
    </style>

</head>
<body>
 
 
<a href="?islem=cikis" title="Çıkış">Çıkış Yap</a><br/><br/>
<?php



if ($_GET["islem"]=="cikis") 
{
    session_destroy();
    header("Location:index.php");
}

echo "Hesap bilgileri<br>";
echo "Kullanıcı adı :".$userData->user->username."<br>";
echo "Ad :".$userData->user->full_name."<br>";
echo "Açıklama :".$userData->user->bio."<br>";
echo "<img src='".$userData->user->profile_picture."'><br>";
echo "Resim Sayısı :".$userData->user->counts->media."<br>";
echo "Takip Edilen Sayısı :".$userData->user->counts->follows."<br>";
echo "Takipçi Sayısı :".$userData->user->counts->followed_by."<br>";

echo "------------------------------<br>";
    
$posts = $instagram->getUserMedia('self',10);

$i=1;
foreach ($posts->data as $k1 => $v1) 
{
    echo $i;
    $i++;
?>

    <div class="kutu">
        <div class="resim">
            <img src="<?=$v1->images->low_resolution->url?>">
        </div>
        <div class="icerik">
            <span class="isim"><?=$v1->user->full_name?></span>
            <span class="bilgi">
                <?php 

                echo "Beğeni :".$v1->likes->count;
                if (isset($v1->caption->text)) {
                    echo " Açıklama :".$v1->caption->text;
                }
                ?>
                    
            </span>
        </div>
    </div>
<?php
}
 
?>
</body>
</html>