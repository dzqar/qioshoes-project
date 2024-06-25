<?php
// error_reporting(0);
  // Memulai session dari login
session_start();

  // Koneksi
include '../../koneksi.php';

  // Tampungan dari proses login
$id = (isset($_SESSION['id_user'])) ? $_SESSION['id_user'] : '0';
$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '0';
$role = (isset($_SESSION['role'])) ? $_SESSION['role'] : '0';

$idSepatu = $_POST['id_sepatu'];
// var_dump($idSepatu);

$tampilDetailSepatu = $gc->tampilSepatu("*","INNER JOIN data_sepatu AS ds ON s.id_sepatu=ds.id_sepatu WHERE ds.id_sepatu='$idSepatu' GROUP BY ds.id_sepatu");
$tampilRating = $gc->tampilRating("INNER JOIN user ON ulasan.id_user=user.id_user INNER JOIN pesanan ON ulasan.id_pesan=pesanan.id_pesan INNER JOIN data_sepatu ON pesanan.id_data_sepatu=data_sepatu.id_data_sepatu WHERE ulasan.id_sepatu=$idSepatu");

  // Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
  header('location: ../form/login/login.php?pesan=belum_login');
  exit;
}elseif(!owner()) {
  header("location: /sepatu/$role/?pesan=noaccess");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Icon Title -->
  <link rel="icon" href="/sepatu/style/logo/logo.png" type="image/x-icon">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.min.css">
  <!-- Style Bootstrap -->
  <link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/css/bootstrap.min.css">
  <script src="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/js/bootstrap.bundle.js"></script>
  <!-- Style CSS -->
  <link rel="stylesheet" href="/sepatu/style/style.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/sweetalert2.min.css">
  <link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/animate.min.css">
  <script src="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/js/sweetalert2.min.js"></script>
  <!-- jQuery -->
  <script src="/sepatu/script/jquery-3.7.1.min.js"></script>
  <!-- Title -->
  <title></title>
</head>
<body>


  <!-- Awal Main -->
  <div class="container-fluid">
    <div class="row">
      <!-- Header -->
      <?php $lib->sidebar();  ?>
      <div class="col p-3 min-vh-100">
        <?php 
        foreach ($tampilDetailSepatu as $d) { 
          ?>
          <div class="row mb-2 justify-content-center border border-black bg-light">
            <!-- Foto Sepatu -->
            <img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>" alt="" style="width: 300px;height: 300px;object-fit: cover;object-position: center center;" />
          </div>

          <div class="container">
            <div class="row mb-2">
              <div class="col">

                <!-- Nama & Merk Sepatu -->
                <h2 class="d-inline">
                  <?= $d['merk_sepatu'].' - '.$d['nama_sepatu'] ?>
                </h2>
                <!-- Rate -->
                <p class="d-inline fs-5">
                  <?php
                  error_reporting(0);
                  $rataRating = $gc->rataRating($idSepatu);
                  if ($rataRating === NULL) {
                    echo '0.0';
                  }else{
                    foreach ($rataRating as $key) { 
                      echo $key['rataRating'];
                    }
                  }?> <i class="bi bi-star-fill"></i>
                </p>

              </div>
            </div>
            <?php
          }
          ?>

          <!-- Kembali -->
          <div class="row mb-3">
            <div class="col">
            <!-- <a href="./detail.php" class='rounded-pill btn me-3' style='
            --bs-btn-color: #fff;
            --bs-btn-bg: #3e3232;
            --bs-btn-hover-color: black;
            --bs-btn-hover-bg: #fff;
            --bs-btn-hover-border-color: #3e3232;
            '>Kembali</a> -->
            <a onclick="history.back()" class='rounded-pill btn me-3' style='
            --bs-btn-color: #fff;
            --bs-btn-bg: #3e3232;
            --bs-btn-hover-color: black;
            --bs-btn-hover-bg: #fff;
            --bs-btn-hover-border-color: #3e3232;
            '>Kembali</a>
          </div>
        </div>

        <!-- Ulasan -->
        <div class="row mb-2 mx-4 justify-content-center">
          <?php 
          if (isset($tampilRating)) {
            foreach ($tampilRating as $d) {?>
              <div class="col-auto mb-3">
                <div class="card bg-secondary-subtle border-black rounded-4" style="width: 18rem;">
                  <div class="card-body">
                    <div class="card-text"><?= $d['username']." | ".$d['rating']?>.0 <i class="bi bi-star-fill"></i></div>
                    <div class="card-text mb-2">Varian : <?= $d['warna'].", ".$d['ukuran']?></div>
                    <p class="card-text"><?= $d['ulasan']?></p>
                  </div>
                </div>
              </div>
              <?php 
            }
          }else{
           ?>
           <div class="col-auto">
             <h4>Belum ada ulasan dari siapapun</h4>
           </div>
         <?php } ?>
       </div>

     </div>
   </div>
 </div>
</div>

<!-- Akhir Main -->

</body>
</html>