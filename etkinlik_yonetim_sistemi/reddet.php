<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["giris"]) || $_SESSION["giris"] != sha1(md5("var"))) {
    echo "<script>alert('Yetkisiz erişim!'); window.location.href='login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $baglanti->query("DELETE FROM kullanicilar WHERE id=$id");
    echo "<script>alert('Kullanıcı silindi.'); window.location.href='onay_bekleyenler.php';</script>";
}
?>
