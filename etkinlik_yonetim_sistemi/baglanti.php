<?php

$host="localhost";
$kullanici="zehra";
$parola="12345";
$vt="etkinlikys";

$baglanti= mysqli_connect($host,$kullanici,$parola,$vt);
mysqli_set_charset($baglanti,"UTF8");




?>