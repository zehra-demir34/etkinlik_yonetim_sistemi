<?php
include("baglanti.php");

$email_err="";
$parola_err="";
$parolatkr_err="";

if(isset($_POST["kaydol"])){

    //email doğrulama
    if(empty($_POST["email"])){
        $email_err="Email alanı boş geçilemez.";

    }
    else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Geçersiz email formatı";
    }
    else{
        $email=$_POST["email"];
    }

    //parola doğrulama
    if(empty($_POST["parola"])){
        $parola_err="Parola boş geçilemez";
    }
    else{
        $parola = password_hash($_POST["parola"], PASSWORD_DEFAULT);
    }

    //parola tekrar doğrulama
    if(empty($_POST["parolatkr"])){
        $parolatkr_err="Parola tekrar kısmı boş geçilemez.";
    }
    else if($_POST["parola"]!=$_POST["parolatkr"]){
        $parolatkr_err="Parolalar eşleşmiyor.";
    }
    else{
        $parolatkr=$_POST["parolatkr"];
    }


    if(isset($email)&&isset($parola)){


        $ekle="INSERT INTO kullanicilar(email,parola,onay) VALUES('$email','$parola',0)";
        $calistirekle= mysqli_query($baglanti,$ekle);

        if($calistirekle){
            echo '<div class="alert alert-success" role="alert">
            Yönetici onay verdikten sonra giriş yapabilirsiniz.
            </div>';
            
        }
        

        mysqli_close($baglanti);
         
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ÜYE KAYIT İŞLEMİ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>ÜYE KAYIT İŞLEMİ</title>
</head>
  <body>
    <style>.baslik-alani {
      margin-top: 100px; /* Aşağıya indirir */
      text-align: center; /* Ortalar */
    }</style>
    <div class="baslik-alani">
    <h3>ATATÜRK ÜNİVERSİTESİ ETKİNLİK YÖNETİM SİSTEMİNE HOŞGELDİNİZ! LÜTFEN GİRİŞ YAPINIZ</h3>
    </div>
    <div class="container p-5">
        <div class="card p-5">
            <form action="kayit.php" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="text" class="form-control
                <?php
                if(!empty($email_err)){
                    echo "is-invalid";
                }

                ?>"

                 id="exampleInputEmail1" name="email">
                <div id="validationServerUsernameFeedback" class="invalid-feedback">
        <?php
            echo $email_err;
        ?>
      </div>
                
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Şifre</label>
                <input type="password" class="form-control 
                <?php
                if(!empty($parola_err)){
                    echo "is-invalid";
                }

                ?>"

                
                id="exampleInputPassword1" name="parola">
                <div id="validationServerUsernameFeedback" class="invalid-feedback">
        <?php
        echo $parola_err;
        ?>
      </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Şifre Tekrar</label>
                <input type="password" class="form-control
                <?php
                if(!empty($parolatkr_err)){
                    echo "is-invalid";
                }

                ?>"

                id="exampleInputPassword1" name="parolatkr">
                <div id="validationServerUsernameFeedback" class="invalid-feedback">
        <?php
        echo $parolatkr_err;
        ?>
      </div>
            </div>
            
            <button type="submit" name="kaydol" class="btn btn-primary">KAYDOL
            </button>
            <?php echo "<br><br>"; ?>
            <a href="login.php">Giriş Yap</a>
            
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>