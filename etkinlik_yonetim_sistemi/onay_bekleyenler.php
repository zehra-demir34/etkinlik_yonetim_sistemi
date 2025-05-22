<?php
session_start();
include("baglanti.php");

// Giriş kontrolü (admin girişi yapılmamışsa çıkışa yönlendir)
if (!isset($_SESSION["giris"]) || $_SESSION["giris"] != sha1(md5("var"))) {
    echo "<script>alert('Yetkisiz erişim!'); window.location.href='yonet-index.php';</script>";
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
   
    <?php
    echo "<h2>Onay Bekleyen Kullanıcılar</h2>";

$sorgu = $baglanti->query("SELECT * FROM kullanicilar WHERE onay=0");

if ($sorgu->num_rows > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<tr><th>ID</th><th>Ad Soyad</th><th>Email</th><th>Kayıt Tarihi</th><th>İşlemler</th></tr>';

    while ($k = $sorgu->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $k['id'] . '</td>';
        echo '<td>' . htmlspecialchars($k['email']) . '</td>';
        echo '<td>' . $k['kayit_tarihi'] . '</td>';
        echo '<td>
                <a href="onayla.php?id=' . $k['id'] . '">✅ Onayla</a> | 
                <a href="reddet.php?id=' . $k['id'] . '" onclick="return confirm(\'Bu kullanıcıyı silmek istediğinize emin misiniz?\')">❌ Reddet</a>
              </td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo "<p>Şu anda onay bekleyen kullanıcı yok.</p>";
}
    
    ?>

    
</body>
</html>