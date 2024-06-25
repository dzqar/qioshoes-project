<?php
  // Memulai session dari login
// error_reporting(0);
session_start();

  // Koneksi
include '../koneksi.php';

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// PAGINATION
$limit = 12; // Limit nampilin per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; //Kalo ada page di URL, maka masuk ke halaman sesuai $_GET['page']. Kalo gaada, otomatis masuk ke halaman pertama
$start = ($page - 1) * $limit; // Awal buat memulai nampilin data (Contoh : "0,10" -> Maka munculin data sampe 10 sesuai $limit)

$tampilSepatu = $gc->tampilSepatu("s.*,ds.harga","INNER JOIN data_sepatu AS ds ON s.id_sepatu=ds.id_sepatu GROUP BY s.id_sepatu LIMIT $start,$limit");
// $lib->nav();

$countTampil = mysqli_query($db->koneksi,"SELECT COUNT(id_sepatu) AS id FROM sepatu");

if (isset($countTampil)) {
  $d = mysqli_fetch_assoc($countTampil);
  $total = $d['id']; // Sama aja kyk foreach atau while data nya
  $pages = ceil($total / $limit); // Membulatkan nilai jika nilainya desimal (0.60 -> 1)
}

$previous = $page - 1; //Mundurin 1 halaman
$next = $page + 1; //Majuin 1 halaman

  // Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
  header('location: /sepatu/?pesan=belum_login');
  exit;
}elseif($_SESSION['idSepatu'] !== "") {
  header('location: ./detail.php?idp='.$_SESSION['idSepatu'].'');
}
// unset($_SESSION['idSepatu']);

// var_dump($_SESSION['idSepatu']);
// echo isset($_SESSION['idSepatu']);
// print_r($_SESSION['idSepatu']);
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
  <title>Beranda</title>
</head>
<body>
  <!-- Pesan -->
  <script>
    <?php include '../script/pesan.js' ?>
  </script>

  <!-- Header -->
  <?php $lib->navCust('active','','') ?>

  <!-- Awal Main -->
  <section id="main">
    <div class="container">
      <div class="row justify-content-center mb-3">
        <?php foreach ($tampilSepatu as $d) { ?>
          <div class="col-auto mx-auto d-flex mb-3">
            <div class="card bg-secondary-subtle border border-black" style="width: 18rem;">
              <div class="card-header bg-light rounded-bottom-4 border-bottom border-black">
                <img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu'] ?>" class="card-img-top d-flex mx-auto" alt="..." style="width: 60%;">
              </div>
              <div class="card-body bg-secondary-subtle rounded-bottom">
                <h5 class="card-title"><?= $d['nama_sepatu']?></h5>
                <div class="card-text"><?= $d['merk_sepatu']?></div>
                <div class="card-text mb-2"><?= rupiah($d['harga'])?></div>
                <form action="./detail.php" method="POST" class="text-end">
                  <input type="number" name="id_sepatu" id="" value="<?= $d['id_sepatu']?>" hidden>
                  <input type="submit" value="Lihat" class="rounded-pill btn" style="
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                  --bs-btn-hover-bg: #fff;
                  --bs-btn-hover-border-color: #3e3232;
                  ">
                </form>
              </div>
            </div>
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
  </section>
  <!-- Akhir Main -->

  <!-- Footer -->
  <?php $lib->footer(); ?>

</body>
</html>