<?php
// error_reporting(0);
  // Memulai session dari login
session_start();

  // Koneksi
include '../koneksi.php';

  // Tampungan dari proses login
$id = (isset($_SESSION['id_user'])) ? $_SESSION['id_user'] : '0';
$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '0';
$role = (isset($_SESSION['role'])) ? $_SESSION['role'] : '0';

function beliBtn($roleCek){
  if ($roleCek !== '0') {
    return '<button name="btn" value="beliSepatu" type="submit" class="rounded-pill btn" style="
    --bs-btn-color: #fff;
    --bs-btn-bg: #3e3232;
    --bs-btn-hover-color: black;
    --bs-btn-hover-bg: #fff;
    --bs-btn-hover-border-color: #3e3232;
    ">Beli</button>';
  }elseif($roleCek === '0'){
    return '<a onclick="loginCek()" class="rounded-pill btn" style="
    --bs-btn-color: #fff;
    --bs-btn-bg: #3e3232;
    --bs-btn-hover-color: black;
    --bs-btn-hover-bg: #fff;
    --bs-btn-hover-border-color: #3e3232;
    ">
    Beli
    </a>';
  }
}

$idSepatu = (isset($_POST['id_sepatu'])) ? $_POST['id_sepatu'] : $_SESSION['idSepatu'];
// var_dump($_SESSION['idSepatu']);
// print_r($_SESSION['idSepatu']);
// echo $_SESSION['idSepatu'];

$tampilDetailSepatu = $gc->tampilSepatu("*","INNER JOIN data_sepatu AS ds ON s.id_sepatu=ds.id_sepatu WHERE s.id_sepatu='$idSepatu' GROUP BY ds.id_sepatu");

if(owner()) {
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
  <!-- Pesan -->
  <script>
    <?php include '../script/pesan.js' ?>
  </script>

  <!-- Header -->
  <?php 
  if($username){
    $lib->navCust('','','');
  }else{
    $lib->navIndex($idSepatu);
  }
  ?>

  <script>
    function loginCek(){
      Swal.fire({
        title: "Anda harus login terlebih dahulu!",
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: "#3e3232",
        cancelButtonColor: "#d33",
        confirmButtonText: "Login",
        cancelButtonText: "Batal"
      }).then((result) => {
        if (result.isConfirmed) {
          // window.location.href='/sepatu/form/login.php';
          // Tampilkan modal Bootstrap
          $('#loginModal').modal('show');
        }
      });
    }
  </script>

  <!-- Awal Main -->
  <section id="main">
    <div class="container-fluid mb-3">
      <?php foreach ($tampilDetailSepatu as $d) { 
        $ids = $d['id_data_sepatu'];
          // var_dump($d['warna']);
        ?>
        <form action="proses.php" method="POST">
          <input type="number" name="id_sepatu" value="<?= $d['id_sepatu']?>" hidden>
          <div class="row mb-2 justify-content-center border border-black bg-light">
            <?php 
            $datalop = mysqli_query($db->koneksi,"SELECT gambar_sepatu AS gs, warna FROM data_sepatu WHERE id_sepatu='$idSepatu' GROUP BY warna");
            // var_dump(mysqli_num_rows($datalop));
            if (mysqli_num_rows($datalop) > 1) {
             ?>
             <div id="carouselExample" class="carousel carousel-dark slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10" style="transition: transform 0s ease, opacity 0s ease-out">
                  <img src="/sepatu/figma/transparent.png" class="d-flex mx-auto" style="width: 300px;height: 300px;object-fit: cover;object-position: center center;">
                </div>
                <?php
                $dataGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu AS gs FROM data_sepatu WHERE id_sepatu='$idSepatu' GROUP BY warna");
                while ($dg = mysqli_fetch_array($dataGambar)) { ?>
                  <div class="carousel-item" data-bs-interval="4000" style="transition: transform 1.2s ease, opacity .5s ease-out">
                    <img src="/sepatu/owner/sepatu/gambar/<?= $dg['gs']?>" class="d-flex mx-auto" style="width: 300px;height: 300px;object-fit: cover;object-position: center center;">
                  </div>
                <?php } ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          <?php }else{ ?>
            <!-- Foto Sepatu -->
            <img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>" style="width: 300px;height: 300px;object-fit: cover;object-position: center center;" /> 
          <?php } ?>
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

          <!-- Ukuran -->
          <div class="row mb-2">
            <div class="col">Ukuran</div>
          </div>
          <div class="row mb-2">
            <div class="col">
              <div class="btn-group">
                <?php 
                /*$dataUkur = mysqli_query($db->koneksi,"SELECT GROUP_CONCAT(warna) AS warna,GROUP_CONCAT(ukuran) AS ukuran, GROUP_CONCAT(stok) AS stok FROM data_sepatu WHERE id_sepatu=1 GROUP BY warna ORDER BY warna,ukuran ASC");*/
                $dataUkur = mysqli_query($db->koneksi,"SELECT GROUP_CONCAT(warna) AS warna, ukuran, GROUP_CONCAT(stok) AS stok FROM data_sepatu WHERE id_sepatu=$idSepatu GROUP BY ukuran ORDER BY ukuran ASC");
                // var_dump($dataUkur);
                while ($du = mysqli_fetch_assoc($dataUkur)) {
                  // print_r($du);
                    // var_dump(explode(',',$du['warna']));echo "<br>";
                  // var_dump(explode(',',$du['ukuran']));echo "<br>";
                  // var_dump(explode(',',$du['stok']));echo "<br>";
                  // var_dump(array_combine(explode(',',$du['warna']),explode(',',$du['stok'])));echo "<br>";
                    /*$exWarna = explode(',',$du['warna']);
                    foreach ($exWarna as $key => $value) {
                      echo "$key : ";
                      echo "$value <br>";
                    }*/
                    // $exStok = explode(',',$du['stok']);
                        // $comArr = array_combine($exWarna);
                        // print_r($comArr);
                    /*$exUkur = explode(',',$du['ukuran']);
                    sort($exUkur);
                    foreach ($exUkur as $key => $value) {*/
                      /*echo "$key : ";
                      echo "$value <br>";*/
                      ?>

                      <input type="radio" class="btn-check" name="ukuran" id="ukuran<?= $du['ukuran']?>" name="ukuran" value="<?= $du['ukuran']?>" autocomplete="off"><?php //echo "=".$du['stok']?>
                      <label class="btn btn-outline-primary" for="ukuran<?= $du['ukuran']?>" style="
                      --bs-btn-color: #3e3232;
                      --bs-btn-border-color: #3e3232;
                      --bs-btn-active-color: #fff;
                      --bs-btn-active-bg: #3e3232;
                      --bs-btn-active-border-color: #3e3232;
                      "><?= $du['ukuran']?></label>

                      <?php
                  // }
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- Warna -->
              <div class="row mb-2">
                <div class="col">Warna</div>
              </div>
              <div class="row mb-2">
                <div class="col">
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <?php 
                    $no = 1;
                    $dataWarna = mysqli_query($db->koneksi,"SELECT warna FROM data_sepatu WHERE id_sepatu='$idSepatu' GROUP BY warna");
                    while ($dW = mysqli_fetch_array($dataWarna)) {
                  // echo $no++; 
                      ?>
                      <!-- // var_dump($dW['warna']); -->
                      <input type="radio" class="btn-check" name="warna" id="warna<?= $dW['warna']?>" name="warna" value="<?= $dW['warna']?>">
                      <label class="btn btn-outline-primary" for="warna<?= $dW['warna']?>" style="
                      --bs-btn-color: #3e3232;
                      --bs-btn-border-color: #3e3232;
                      --bs-btn-active-color: #fff;
                      --bs-btn-active-bg: #3e3232;
                      --bs-btn-active-border-color: #3e3232;
                      "><?= $dW['warna']?></label>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- Jumlah Beli -->
              <div class="row mb-2">
                <div class="col">
                  <table>
                    <tr>
                      <td>Jumlah Beli</td>
                      <td>:</td>
                      <td>
                        <input type="number" name="jumlahBeli" min="1"  class="form-control-sm border border-black" value="1">
                      </td>
                    </tr>
                  </table>
                </div>
              </div>

              <div class="row mb-2">
                <div class="col">
                  <div class="row mb-2 align-items-center">
                    <!-- Link Lihat Semua Ulasan -->
                    <div class="col-auto">
                      <form action="proses.php" method="POST"></form>
                      <a onclick="window.location.href='./proses.php?btn=lihatUlasan&id_sepatu=<?= $d['id_sepatu']?>'" class="link-dark text-decoration-underline">Lihat Semua Ulasan</a>
                    </div>
                    <div class="col-auto">
                      <a class="link-dark text-decoration-underline" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Lihat Deskripsi
                      </a>
                    </div>
                    <div class="row my-2">
                      <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                          <?= $d['deskripsi']?>
                        </div>
                      </div>  
                    </div>
                    <!-- Kembali & Beli -->
                    <div class="col text-end">
                      <?php 
                      if ($role === '0') {?>
                        <a onclick="history.back()" class='rounded-pill btn me-3' style='
                        --bs-btn-color: #fff;
                        --bs-btn-bg: #3e3232;
                        --bs-btn-hover-color: black;
                        --bs-btn-hover-bg: #fff;
                        --bs-btn-hover-border-color: #3e3232;
                        '>Kembali</a>
                       <?= beliBtn($role);?>
                      <?php }else{ ?>
                       <a onclick="window.location.href='./'" class='rounded-pill btn me-3' style='
                       --bs-btn-color: #fff;
                       --bs-btn-bg: #3e3232;
                       --bs-btn-hover-color: black;
                       --bs-btn-hover-bg: #fff;
                       --bs-btn-hover-border-color: #3e3232;
                       '>Kembali</a>
                       <?= beliBtn($role);?>
                     <?php } ?>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </form>
       <?php } ?>
     </div>
   </section>
   <!-- Akhir Main -->

   <!-- Footer -->
   <?php $lib->footer() ?>

 </body>
 </html>
 <?php $_SESSION['idSepatu'] = "" ?>