<?php
    session_start();
    include("baglanti.php");

    if ($_SESSION["giris"] != sha1(md5("var")) || $_COOKIE["kullanici"] != "msb") {
        header("Location: cikis.php");
    }

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yönetim Paneli Ana Sayfa</title>
</head>
<body>

    <div style="text-align:center;">
        <a href="yonet-anasayfa.php">ANA SAYFA</a> - <a href="yonet-etkinlik.php">ETKİNLİKLER</a>-<a href="onay_bekleyenler.php">KULLANICI ONAY</a>-<a href="duyurular.php">DUYURULAR</a>  - <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) return false;">ÇIKIŞ</a>
        <br><hr><br><br>
    </div>

    <h2 style="text-align:center;"> Menüden Seçim Yapınız </h2>

    

</body>
</html>