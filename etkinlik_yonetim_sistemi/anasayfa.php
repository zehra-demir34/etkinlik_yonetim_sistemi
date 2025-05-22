<?php
  include("baglanti.php");
  session_start();



function gunlukGenelHavaDurumu($tarih) {
    $apiKey = "939637607f535ba1c74ee9d5e59c0b51";
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=Erzurum,tr&units=metric&lang=tr&appid={$apiKey}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $cevap = curl_exec($ch);
    curl_close($ch);

    $veri = json_decode($cevap, true);
    if (!$veri || !isset($veri["list"])) return "bilinmiyor";

    $havaDurumlari = [];

    foreach ($veri["list"] as $tahmin) {
        $tahminTarihi = substr($tahmin["dt_txt"], 0, 10);
        if ($tahminTarihi === $tarih) {
            $aciklama = $tahmin["weather"][0]["description"];
            $havaDurumlari[] = $aciklama;
        }
    }

    if (empty($havaDurumlari)) return "bilinmiyor";

    // En çok tekrar eden hava durumu
    $havaSayilari = array_count_values($havaDurumlari);
    arsort($havaSayilari);
    $enYayginHava = key($havaSayilari);

    return $enYayginHava;
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
  <title>Etkinlik Yönetim Sistemi</title>
  <link rel="stylesheet" href="style.css"/>


  <!--! KULLANICI MAİLİ GÖSTERME -->
  <?php
  $kullanici_mail =$_SESSION['email'];
  ?>
<script>
  const kullaniciMail = "<?php echo htmlspecialchars($kullanici_mail); ?>";
</script>
<script>
  function kullaniciMailGoster() {
    alert("Giriş yapan kullanıcı maili: " + kullaniciMail);
  }
</script>



</head>
<body>
  <header class="header">
    <a href ="#" class="logo">
    <img src="images/uni_logo.png" alt="logo" width="100"/>
    </a>

    <nav class="navbar">
      <a href="anasayfa.php">Anasayfa</a>
      <a href="anasayfa.php">Etkinlikler</a>
      <a href="sepet.php">Sepet</a>
    </nav>
    <div class="buttons">
      <button onclick="window.location.href='sepet.php';"><i class="fas fa-shopping-cart" class="badge cart-count"></i></button>

      <button onclick="kullaniciMailGoster();"><i class="fa-solid fa-circle-user"></i></button>
      <p id="kullaniciMailYazisi" style="display:none; color:blue;"></p>

      <script>
        function kullaniciMailGoster() {
          const paragraf = document.getElementById("kullaniciMailYazisi");
          paragraf.textContent = "Giriş yapan kullanıcı maili: " + kullaniciMail;
          paragraf.style.display = "block";
        }
      </script>

      
      <button onclick="window.location.href='cikis.php';">
        <i class="fa-solid fa-right-to-bracket"></i>
      </button>
    </div>
  </header>

  <!--! ANA SAYFA -->
  <section class="home">
    <div class="unibanner"></div>
    <div class="content">
      <h3>ATATÜRK ÜNİVERSİTESİ ETKİNLİKLERİ</h3>  
      <img src="images/AnaSayfa.jpg" alt="home"/>
      <p><h3>Okulumuz geniş imkanları ile siz değerli öğrencilerimize geniş bir etkinlik havuzu sunuyor.</h3></p>
      <a href="#" class="btn">Hadi İlgi Alanına Göre Bir Etkinlik Seç!</a>
    </div>
  </section>

  <section class="Etkinlikler">



      <h2 style="text-align:center;">Etkinlikler</h2>
<div class="container">
    <?php
    if ($baglanti) {
        $sql = "SELECT * FROM etkinlikler";
        $sonuc = mysqli_query($baglanti, $sql);
        

        if ($sonuc && mysqli_num_rows($sonuc) > 0) {
            while ($row = mysqli_fetch_assoc($sonuc)) {
                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($row['resim']) . '" alt="Etkinlik Görseli">';
                echo '<h3>' . htmlspecialchars($row['etkinlik_ismi']) . '</h3>';
                echo '<p>Fiyat: ' . htmlspecialchars($row['fiyat']) . ' TL</p>';
                $tarih = $row['tarih'];
                $genelHava = gunlukGenelHavaDurumu($tarih);
                echo "<p>Hava durumu: " . htmlspecialchars($genelHava) . "</p>";
                echo '<p>Kontenjan: ' . htmlspecialchars($row['kontenjan']) . '</p>';
                echo '<p>Tarih: ' . htmlspecialchars($row['tarih']) . '</p>';
                echo '<form method="post" action="sepet.php">';
                echo '<input type="hidden" name="etkinlik_id" value="' . htmlspecialchars($row['id']) . '">';
                echo '<input type="hidden" name="etkinlik_adi" value="' . htmlspecialchars($row['etkinlik_ismi']) . '">';
                echo '<input type="hidden" name="fiyat" value="' . htmlspecialchars($row['fiyat']) . '">';
                echo '<input type="hidden" name="adet" value="1">';
                echo '<button type="submit" name="sepete_ekle" class="btn btn-primary btn-block">';
                echo '<i class="fas fa-shopping-cart"></i> Sepete Ekle';
                echo '</button>';
                echo '</form>';
                echo '</div>';
              
                              
              }
        } 
    } 
    ?>
</div>


  </section>

  <section id="duyurular">

  <div class="container" >
    <h2>DUYURULAR</h2>
    <hr>
    <div class="temizle"></div>
    <?php
      $sorgu = $baglanti->query("SELECT * FROM duyurular");
      $satir = $sorgu->fetch_object();
      echo "<h3>".$satir->duyuru."</h3>";
    ?>
    </div>
  </section>
<hr>
  <section id="oneriler">
  <div class="container" >
    <h2>ÖNERİLER</h2>
    <hr>
    <div class="temizle"></div>
    <?php
      echo "<h3><ul>
      <li>Eğer resme ilginiz varsa tuval boyama ve ebru etkinlikleri tam sizlik.</li>
      <li>Programlamaya merakınız varsa programlama etkinliğini kaçırmayın derim.</li>
      </ul></h3>";
    ?>
    </div>
  </section>

<script>
  function kullaniciMailGoster() {
    alert("Giriş yapan kullanıcı maili: " + kullaniciMail);
  }
</script>


</body>
</html>

