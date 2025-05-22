<?php
    session_start();
    include("baglanti.php");

    if ($_SESSION["giris"] != sha1(md5("var")) || $_COOKIE["kullanici"] != "msb") {
        header("Location: cikis.php");
    }

    if ($_POST) {
        $duyuru = $_POST["duyuru"];
        $sorgu = $baglanti->query("delete from duyurular");
        $sorgu = $baglanti->query("insert into duyurular (duyuru) values ('$duyuru')");
        echo "<script> window.location.href='duyurular.php'; </script>";
    }

    $sorgu = $baglanti->query("select * from duyurular");
    $satir = $sorgu->fetch_object();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yönetim Paneli Duyurular</title>
</head>
<body style="text-align:center;">

    <div style="text-align:center;">
        <a href="anasayfa.php">ANA SAYFA</a> - <a href="yonet-etkinlik.php">ETKİNLİKLER</a> -<a href="onay_bekleyenler.php">KULLANICI ONAY</a>- <a href="duyurular.php">DUYURULAR</a>  - <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) return false;">ÇIKIŞ</a>
        <br><hr><br><br>
    </div>

    <form action="" method="post">
        <b>İçerik:</b><br><br>
        <textarea style="width:80%;height:300px;" name="duyuru"><?php echo $satir->duyuru; ?></textarea><br><br>
        <input type="submit" value="Kaydet">
    </form>

    

</body>
</html>