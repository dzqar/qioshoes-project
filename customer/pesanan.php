<?php
error_reporting(0);
  // Memulai session dari login
session_start();
// unset($_SESSION['idSepatu']);
$_SESSION['idSepatu'] = "";

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

$tampilDaftarPesanan = $p->tampilDaftarPesanan("WHERE pesanan.id_user='$id' ORDER BY id_pesan DESC LIMIT $start,$limit");

$countTampil = mysqli_query($db->koneksi,"SELECT COUNT(id_pesan) AS id FROM pesanan WHERE id_user='$id'");

if (isset($countTampil)) {
  $d = mysqli_fetch_assoc($countTampil);
  $total = $d['id']; // Sama aja kyk foreach atau while data nya
  $pages = ceil($total / $limit); // Membulatkan nilai jika nilainya desimal (0.60 -> 1)
}

$previous = $page - 1; //Mundurin 1 halaman
$next = $page + 1; //Majuin 1 halaman

// var_dump($_SESSION['idSepatu']);
// $lib->nav();

  // Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
  header('location: /sepatu/form/login/login.php?pesan=belum_login');
  exit;
}

if (owner()) {
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
  <title>Beranda</title>
</head>
<body>
  <!-- Pesan -->
  <script>
    <?php include '../script/pesan.js' ?>
  </script>
  
  <!-- Header -->
  <?php $lib->navCust('','active','') ?>

  <!-- Awal Main -->
  <section id="main">
    <div class="container-fluid">
      <div class="row">
        <!-- content -->
        <div class="col p-3 min-vh-100">
          <h2 class="text-center"><a href="./" class="bi bi-arrow-left text-black"></a> Daftar Pesanan</h2>
          <div class="row justify-content-center mx-auto">
            <?php 
            $no = 1;
            if (isset($tampilDaftarPesanan)) {
              foreach ($tampilDaftarPesanan as $d) { ?>
                <div class="col-auto mx-1 mb-3">
                  <div class="card border border-black h-100" style="width: 18rem;background-color: <?= $lib->setCardColor($d['status'])?>">
                    <div class="card-header bg-light rounded-bottom-3 border-bottom border-black">
                      <?php 
                      $idSepatu = $d['id_sepatu'];
                      $ids = $d['id_data_sepatu'];
                      $warna = $d['warna'];
                      /*if (isset($warna)) {
                        $dataGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE id_sepatu=$idSepatu AND gambar_sepatu LIKE '%$warna%'");
                      }else{
                        $dataGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE id_sepatu=$idSepatu");
                      }
                      $dg = mysqli_fetch_assoc($dataGambar);*/
                      $dataWarna = mysqli_query($db->koneksi,"SELECT gambar_sepatu AS gs FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE data_sepatu.id_data_sepatu=$ids");
                      $dg = mysqli_fetch_assoc($dataWarna);
                      ?>
                      <?= $dg['gambar_sepatu']?>
                      <img src="/sepatu/owner/sepatu/gambar/<?= $dg['gs']?>" alt="" class="card-img-top d-flex mx-auto" style="width: 50%;">
                    </div>
                    <div class="card-body rounded-bottom">
                      <h5 class="card-title"><?= $no++.". ".$d['merk_sepatu']?></h5>
                      <div class="card-text text-truncate" title="<?= $d['merk_sepatu']." ".$d['nama_sepatu']; ?>"><?= $d['nama_sepatu']; ?></div>
                      <div class="card-text">Varian : <?= $d['warna'].', '.$d['ukuran']?></div>
                      <div class="card-text">Jumlah : <?= $d['jumlah_beli']?></div>
                      <div class="card-text">Total : <?= rupiah($d['total_biaya']) ?></div>
                      <div class="card-text">Tanggal Expired : <?= $d['tgl_exp'] ?></div>
                      <div class="card-text">Status : <?= $d['status'] ?></div>
                      <div class="card-text text-end mt-2">
                        <?php 
                        $dataRate = mysqli_query($db->koneksi,"SELECT * FROM ulasan WHERE id_user='$id' AND id_sepatu='$idSepatu'");
                        $cekRate = mysqli_num_rows($dataRate);
                        echo $lib->btnBatal($id,$d['status'],$d['id_pesan'],$d['id_sepatu'],$cekRate) ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php }}else{ ?>
                <div class="col-auto mx-1 mb-3">
                  <h4>Anda belum melakukan pemesanan apapun !</h4>
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
      </div>
    </section>
    <!-- Akhir Main -->

    <!-- Footer -->
    <?php $lib->footer() ?>

  </body>
  </html>