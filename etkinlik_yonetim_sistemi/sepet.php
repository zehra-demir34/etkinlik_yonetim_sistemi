<?php
include("baglanti.php");
session_start();

// Sepet oluşturulmamışsa oluştur
if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

// Sepete ekleme işlemi
// Giriş yapmış kullanıcı ID'si
$kullanici_id = $_SESSION['kullanici_id']; // Kullanıcının girişte oturuma atandığını varsayıyoruz

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sepete_ekle'])) {
    $id = $_POST['etkinlik_id'];
    $ad = $_POST['etkinlik_adi'];
    $fiyat = (float) $_POST['fiyat'];
    $adet = (int) $_POST['adet'];


        // Etkinliğin mevcut kontenjanını kontrol et
    $kontenjanSorgu = mysqli_query($baglanti, "SELECT kontenjan FROM etkinlikler WHERE id = $id");
    $kontenjanSonuc = mysqli_fetch_assoc($kontenjanSorgu);
    $mevcutKontenjan = $kontenjanSonuc['kontenjan'];

    if ($mevcutKontenjan >= $adet) {
        // Sepete ekleme işlemi devam etsin

        // Varsa adeti artır
        $varmi = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id=$kullanici_id AND etkinlik_id=$id");

        if (mysqli_num_rows($varmi) > 0) {
            mysqli_query($baglanti, "UPDATE sepet SET adet = adet + $adet WHERE kullanici_id=$kullanici_id AND etkinlik_id=$id");
        } else {
            mysqli_query($baglanti, "INSERT INTO sepet(kullanici_id, etkinlik_id, etkinlik_adi, fiyat, adet) 
                                     VALUES ($kullanici_id, $id, '$ad', $fiyat, $adet)");
        }

        // Etkinliğin kontenjanını azalt
        mysqli_query($baglanti, "UPDATE etkinlikler SET kontenjan = kontenjan - $adet WHERE id = $id");

        header("Location: sepet.php");
        exit;
    } else {
        // Yetersiz kontenjan
        echo "<script>alert('Yeterli kontenjan yok. Mevcut kontenjan: $mevcutKontenjan'); window.location.href='anasayfa.php';</script>";
        exit;
    }

    

    // Aynı etkinlik sepette varsa adeti artır
    $varmi = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id=$kullanici_id AND etkinlik_id=$id");
    
    if (mysqli_num_rows($varmi) > 0) {
        mysqli_query($baglanti, "UPDATE sepet SET adet = adet + $adet WHERE kullanici_id=$kullanici_id AND etkinlik_id=$id");
    } else {
        mysqli_query($baglanti, "INSERT INTO sepet(kullanici_id, etkinlik_id, etkinlik_adi, fiyat, adet) 
                                 VALUES ($kullanici_id, $id, '$ad', $fiyat, $adet)");
    }

    header("Location: sepet.php");
    exit;
}


// Sepetten çıkarma işlemi ve kontenjan geri artırma
if (isset($_GET['sil'])) {
    $etkinlik_id = $_GET['sil'];
    $kullanici_id = $_SESSION['kullanici_id'];

    // Önce bu üründen kaç adet vardı, öğren
    $adetSorgu = mysqli_query($baglanti, "SELECT adet FROM sepet WHERE kullanici_id = $kullanici_id AND etkinlik_id = $etkinlik_id");
    $adetSatir = mysqli_fetch_assoc($adetSorgu);
    $adet = (int) $adetSatir['adet'];

    // Etkinlik kontenjanını geri artır
    mysqli_query($baglanti, "UPDATE etkinlikler SET kontenjan = kontenjan + $adet WHERE id = $etkinlik_id");

    // Sepetten sil
    mysqli_query($baglanti, "DELETE FROM sepet WHERE kullanici_id = $kullanici_id AND etkinlik_id = $etkinlik_id");

    header("Location: sepet.php");
    exit;
}

?>





<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
  <title>Etkinlik Yönetim Sistemi</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="style1.css"/>


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

    

      <button onclick="window.location.href='cikis.php';">
        <i class="fa-solid fa-right-to-bracket"></i>
      </button>
    </div>
  </header>

</head>
<body>

    
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ödeme Modalı -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="odemeForm">
            <div class="modal-header">
              <h5 class="modal-title">Ödeme Bilgileri</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Kart Üzerindeki İsim</label>
                <input type="text" class="form-control" name="adSoyad" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Kart Numarası</label>
                <input type="text" class="form-control" name="kartNumarasi" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" required>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label class="form-label">Son Kullanma</label>
                  <input type="text" class="form-control" name="sonKullanma" placeholder="AA/YY" maxlength="5" required>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label">CVV</label>
                  <input type="password" class="form-control" name="cvv" maxlength="4" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Tutar (₺)</label>
                <input type="number" class="form-control" name="tutar" min="1" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" id="odemeBtn">Ödemeyi Tamamla</button>

              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap ve JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function showPaymentModal() {
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
      }
    </script>



   
   <div class="container" style="max-width: 900px; margin: auto;">
    <h1>SEPET</h1>
    

    <table>
      <thead>
        <tr>
         
          <th>Etkinlik Adı</th>
          <th>Fiyat</th>
          <th>Bilet Adeti</th>
          <th>Toplam</th>
          <th>Sepetten Çıkar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $kullanici_id = $_SESSION['kullanici_id'];

        $sepetSorgu = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id = $kullanici_id");

        $toplamTutar = 0;
        $toplamAdet = 0;

        while ($urun = mysqli_fetch_assoc($sepetSorgu)) {
            $toplam = $urun['fiyat'] * $urun['adet'];
            $toplamTutar += $toplam;
            $toplamAdet += $urun['adet'];
            echo '<tr>';
            echo '<td>' . htmlspecialchars($urun['etkinlik_adi']) . '</td>';
            echo '<td>' . $urun['fiyat'] . ' TL</td>';
            echo '<td>' . $urun['adet'] . '</td>';
            echo '<td>' . $toplam . ' TL</td>';
            echo '<td><a href="sepet.php?sil=' . $urun['etkinlik_id'] . '" class="btn-danger">Sil</a></td>';
            echo '</tr>';
        }
        ?>
       
      </tbody>
        <tfoot>
            <tr>
          <td colspan="3" style="text-align:right;"><strong>Toplam:</strong></td>
          <td colspan="2"><strong><?php echo $toplamTutar; ?> TL</strong></td>
        </tr>
        </tfoot>

        
    </table>

    
    <button style="align:right" onclick="showPaymentModal()" class="btn btn-primary">SEPETİ ONAYLA</button>
    

  </div>



<script>
  document.getElementById("odemeBtn").addEventListener("click", function() {
    alert("Ödeme tamamlandı. Sepetiniz onaylandı!");
  });
</script>


</body>
</html>
