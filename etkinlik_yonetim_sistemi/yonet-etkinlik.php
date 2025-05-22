<?php
session_start();
include("baglanti.php");

// Giriş kontrolü
if ($_SESSION["giris"] != sha1(md5("var")) || $_COOKIE["kullanici"] != "msb") {
    header("Location: cikis.php");
    exit;
}


?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yönetim Paneli - Etkinlikler</title>
</head>
<body style="text-align:center;">

    <div>
        <a href="yonet-anasayfa.php">ANA SAYFA</a> -
        <a href="yonet-etkinlik.php">ETKİNLİKLER</a> -
        <a href="onay_bekleyenler.php">KULLANICI ONAY</a>-
        <a href="duyurular.php">DUYURULAR</a> -
        <a href="cikis.php" onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?');">ÇIKIŞ</a>
    </div>

    <hr><br>
    <h2>Etkinlik eklemek için veri tabanına gidiniz.</h2>


    
</body>
</html>
