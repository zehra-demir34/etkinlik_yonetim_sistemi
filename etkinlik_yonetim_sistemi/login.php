<?php
include("baglanti.php");
//session_start();

$email_err="";
$parola_err="";


if(isset($_POST["giris"])){

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
        $parola = $_POST["parola"];
    }

    

    if(isset($email)&&isset($parola)){

        $secim="SELECT * FROM kullanicilar WHERE email='$email'";
        $calistir=mysqli_query($baglanti,$secim);
        $kayitsayisi=mysqli_num_rows($calistir);

        if($kayitsayisi>0){

            $ilgilikayit=mysqli_fetch_assoc($calistir);
            $hashlisifre=$ilgilikayit["parola"];

            if(password_verify($parola,$hashlisifre)){
                session_start();
                $_SESSION["email"]=$ilgilikayit["email"];
                $_SESSION["kullanici_id"] = $ilgilikayit["id"];
                header("location:anasayfa.php");

            }
            else{
                echo '<div class="alert alert-danger" role="alert">
            Şifre Yanlış.
            </div>';
            }

        }
        else{
            echo '<div class="alert alert-danger" role="alert">
            Mail Adresi Yanlış.
            </div>';
        }
        


       // mysqli_close($baglanti);
        
    }

}

session_start();
    
    if ($_POST) {
        $email = $_POST["email"];
        $parola = $_POST["parola"];

        $sorgu = $baglanti->query("select * from kullanicilar where (email='$email' && parola='$parola' && onay=1)");
        $kayitsay = $sorgu->num_rows;

        if ($kayitsay > 0) {
            setcookie("kullanici","msb",time()+60*60);
            $_SESSION["giris"] = sha1(md5("var"));

            echo "<script> window.location.href='yonet-anasayfa.php'; </script>";
        } else {
            echo "<script>
            alert('HATALI KULLANICI BİLGİSİ!'); window.location.href='login.php';
            </script>";
        }
    }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ÜYE GİRİŞ İŞLEMİ</title>
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
            <form action="login.php" method="POST">
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
          
        
            
            <button type="submit" name="giris" class="btn btn-primary">GİRİŞ YAP
            </button>

                <?php echo "<br><br> Kaydolmadıysan";?>
                <a href="kayit.php">Kayıt Ol</a>

            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>
