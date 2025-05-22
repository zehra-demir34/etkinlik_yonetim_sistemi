<?php
    session_start();
    include("baglanti.php");
    if ($_POST) {
        $email = $_POST["email"];
        $parola = $_POST["parola"];

        $sorgu = $baglanti->query("select * from kullanicilar where (email='$email' && parola='$parola')");
        $kayitsay = $sorgu->num_rows;

        if ($kayitsay > 0) {
            setcookie("kullanici","msb",time()+60*60);
            $_SESSION["giris"] = sha1(md5("var"));

            echo "<script> window.location.href='yonet-anasayfa.php'; </script>";
        } else {
            echo "<script>
            alert('HATALI KULLANICI BİLGİSİ!'); window.location.href='yonet-index.php';
            </script>";
        }
    }
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YÖNETİCİ PANELİ GİRİŞ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>YÖNETİCİ PANELİ GİRİŞ</title>
</head>
  <body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="yonet-index.php" method="POST">
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
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>