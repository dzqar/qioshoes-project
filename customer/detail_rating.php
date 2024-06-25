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

$idSepatu = $_SESSION['idSepatu'];
if ($idSepatu === "") {
  header('location: /sepatu/customer/pesanan.php?pesan=noSessionDetected');
}
// $idUser = (isset($_POST['id_user'])) ? $_POST['id_user'] : 0;
// var_dump($idSepatu);

// PAGINATION
$limit = 12; // Limit nampilin per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; //Kalo ada page di URL, maka masuk ke halaman sesuai $_GET['page']. Kalo gaada, otomatis masuk ke halaman pertama
$start = ($page - 1) * $limit; // Awal buat memulai nampilin data (Contoh : "0,10" -> Maka munculin data sampe 10 sesuai $limit)

$tampilDetailSepatu = $gc->tampilSepatu("*","INNER JOIN data_sepatu AS ds ON s.id_sepatu=ds.id_sepatu WHERE ds.id_sepatu='$idSepatu' GROUP BY ds.id_sepatu");
$tampilRating = $gc->tampilRating("INNER JOIN user ON ulasan.id_user=user.id_user INNER JOIN pesanan ON ulasan.id_pesan=pesanan.id_pesan INNER JOIN data_sepatu ON pesanan.id_data_sepatu=data_sepatu.id_data_sepatu WHERE ulasan.id_sepatu=$idSepatu LIMIT $start,$limit");

$countTampil = mysqli_query($db->koneksi,"SELECT COUNT(id_ulasan) AS id FROM ulasan");

if (isset($countTampil)) {
  $d = mysqli_fetch_assoc($countTampil);
  $total = $d['id']; // Sama aja kyk foreach atau while data nya
  $pages = ceil($total / $limit); // Membulatkan nilai jika nilainya desimal (0.60 -> 1)
}

$previous = $page - 1; //Mundurin 1 halaman
$next = $page + 1; //Majuin 1 halaman

  // Jika pengguna belum melakukan login
/*if(!isset($_SESSION['username'])){
  header('location: ../form/login/login.php?pesan=belum_login');
  exit;
}*/

/*  // Fungsi untuk memeriksa izin akses sesuai role kasir
include '../script/cek_akses.php';

  // Kalo misalnya akun dengan role lain selain customer, bakal dilempar lagi ke halamannya

if (!customer()) {
  header("location:../$role/?pesan=noaccess");
}*/
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

  <!-- Header -->
  <?php 
  if($username){
    $lib->navCust('','','') ;
  }else{
    $lib->navIndex(NULL);
  }
  ?>

  <script>
    // Function showPass yang baru
    $(document).ready(function() {
      // Ketika <a> yang didalam <div id="pw"> di click
      $("#pw a").on('click', function(event) {
        // Membatalkan tindakan onclick (kalo di nonaktifin, href="" nya bakal jalan)
        event.preventDefault();
        // Jika <input type="text">
        if($('#pw input').attr("type") == "text"){
          // Mengubahnya menjadi "password"
          $('#pw input').attr('type', 'password');
          // Menambah class di <i> atau icon mata nya
          $('#pw i').addClass( "bi-eye-slash" ); //Icon mata dicoret
          // Menghapus class
          $('#pw i').removeClass( "bi-eye" ); //Icon mata biasa
          // Jika <input type="password">
        }else if($('#pw input').attr("type") == "password"){
          // Mengubahnya menjadi text
          $('#pw input').attr('type', 'text');
          // Menghapus class
          $('#pw i').removeClass( "bi-eye-slash" );
          // Menambah class
          $('#pw i').addClass( "bi-eye" );
        }
      });
    });
  </script>

  <!-- Awal Main -->
  <section id="main">
    <div class="container-fluid mb-3">
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
                <div class="card border-black bg-secondary-subtle rounded-4" id="<?= md5($d['id_user'])?>" style="width: 18rem;">
                  <div class="card-body">
                    <div class="card-text"><?= $d['username']." | ".$d['rating']?>.0 <i class="bi bi-star-fill"></i></div>
                    <div class="card-text mb-2">Varian : <?= $d['warna'].", ".$d['ukuran']?></div>
                    <p class="card-text"><?= $d['ulasan']?></p>
                  </div>
                </div>
              </div>
              <script>
                // Buat warna card
                const url = window.location.href;
                const hash = url.split('#')[1];
                const userId = hash ? hash : '';

                const userDiv = document.getElementById("<?= md5($d['id_user'])?>");
                if (userId) {
                  userDiv.classList.add('bg-success-subtle');
                } else {
                  userDiv.classList.add('bg-secondary-subtle');
                }
              </script>
              <?php 
            }
          }else{
           ?>
           <div class="col-auto">
             <h4>Belum ada ulasan dari siapapun</h4>
           </div>
         <?php } ?>
       </div>
       <!-- Sistem Pagination -->
       <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center" style="
        --bs-pagination-color: #3e3232;
        --bs-pagination-border-color: #000;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-hover-bg: #3e3232;
        --bs-pagination-active-color: #fff;
        --bs-pagination-active-border-color: #000;
        --bs-pagination-disabled-border-color: #000;
        --bs-pagination-active-bg: #3e3232;
        ">

        <!-- Previous -->
        <li class="page-item <?php if($page === 1 || $_GET['page'] === '1'){/*Akan disabled ketika masih di awal page*/ echo 'disabled';} ?>">
          <a class="page-link" href="?page=<?= $previous ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>

        <?php for ($i=1; $i <= $pages ; $i++) { 
          $angka = $i.""; ?>

          <!-- Link akan menjadi berwarna/active ketika sesuai dengan link -->
          <li class="page-item
          <?php if (isset($_GET['page']) && $_GET['page'] === $angka) { 
            /*Tombol akan aktif ketika ada ?page di URL dan sesuai dengan angka*/ 
            echo 'active'; 
            } else if (!isset($_GET['page']) && $i === 1) {
              /*Tombol akan aktif ketika tidak ada ?page di URL*/
              echo 'active'; 
              } else {
                echo ''; 
              }?>">
              <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php } 
          ?>

          <!-- Next -->
          <li class="page-item <?php if(isset($_GET['page']) && ($_GET['page'] === $angka) || ($total <= $limit)) { echo 'disabled'; } ?>">
            <a class="page-link" href="?page=<?= $next ?>">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>

    </div>
  </div>
</section>
<!-- Akhir Main -->

<!-- Footer -->
<?php $lib->footer() ?>

</body>
</html>
<?php //$_SESSION['idSepatu'] = "" ?>