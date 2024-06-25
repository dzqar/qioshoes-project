<?php
// error_reporting(0);
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../koneksi.php';

// PAGINATION
$limit = 12; // Limit nampilin per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; //Kalo ada page di URL, maka masuk ke halaman sesuai $_GET['page']. Kalo gaada, otomatis masuk ke halaman pertama
$start = ($page - 1) * $limit; // Awal buat memulai nampilin data (Contoh : "0,10" -> Maka munculin data sampe 10 sesuai $limit)

$tampilDaftarPesanan = $p->tampilDaftarPesanan("ORDER BY status ASC LIMIT $start,$limit");

$countTampil = mysqli_query($db->koneksi,"SELECT COUNT(id_pesan) AS id FROM pesanan");

if (isset($countTampil)) {
	$d = mysqli_fetch_assoc($countTampil);
	$total = $d['id']; // Sama aja kyk foreach atau while data nya
	$pages = ceil($total / $limit); // Membulatkan nilai jika nilainya desimal (0.60 -> 1)
}

$previous = $page - 1; //Mundurin 1 halaman
$next = $page + 1; //Majuin 1 halaman

  // Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
	header('location: /sepatu/form/login.php?pesan=belum_login');
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
	<link rel="icon" href="../../style/logo/logo.png" type="image/x-icon">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="../../style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.css">
	<link rel="stylesheet" href="../../style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.min.css">
	<!-- Style Bootstrap -->
	<link rel="stylesheet" href="../../style/FR FRONTEND/BOOSTRAP/assets/css/bootstrap.min.css">
	<script src="../../style/FR FRONTEND/BOOSTRAP/assets/js/bootstrap.bundle.min.js"></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="../../style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../../style/FR FRONTEND/SWEETALERT2/assets/css/sweetalert2.min.css">
	<link rel="stylesheet" href="../../style/FR FRONTEND/SWEETALERT2/assets/css/animate.min.css">
	<script src="../../style/FR FRONTEND/SWEETALERT2/assets/js/sweetalert2.min.js"></script>
	<title>Daftar Pesanan - Owner</title>
</head>
<body>
	<!-- Pesan -->
	<script>
		<?php include '../../script/pesan.js' ?>
	</script>
	<div class="container-fluid">
		<div class="row">
			<?php $lib->sidebar() ?>
			<!-- content -->
			<div class="col p-3 min-vh-100">
				<h2 class="text-center">Daftar Pesanan</h2>
				<div class="row justify-content-center mx-auto">
					<?php 
					/*function setCardColor($status) {
						switch ($status) {
							case 'Belum Diterima':
							return '#D9D9D9';
							case 'Dikemas':
							return '#FFD88C';
							case 'Siap Diambil':
							return '#FFFD8C';
							case 'Selesai':
							return '#95FF8C';
							case 'Batal':
							case 'Tolak':
							case 'Kadaluarsa':
							return '#FF8C8C';
							case 'Penilaian':
							return '#8CFFEA';
							default:
					      return 'white'; // Warna default jika status tidak ditemukan
					  }
					}*/
					/*function btnAction($status,$idPesan) {
						switch ($status) {
							case 'Belum Diterima':
							return "<form action='tolak.php' method='POST'>
							<a href='proses.php?' class='btn btn-success'>Cek Bukti</a>
							<input type='number' name='id_pesan' value='$idPesan' hidden><input type='submit' value='Tolak' class='btn btn-danger' />
							<a href='proses.php?btn=Terima&id_pesan=$idPesan' class='btn btn-success'>Terima</a>
							</form>";
							case 'Dikemas':
							return "<a href='proses.php?' class='btn btn-success'>Cek Bukti</a>
							<a href='proses.php?id_pesan=$idPesan&btn=siapDiambil' class='btn btn-success'>Selesaikan</a>";
							default:
							return "<a href='proses.php?' class='btn btn-success'>Cek Bukti</a>";
						}
					}*/
					$no = 1;
					foreach ($tampilDaftarPesanan as $d) { ?>
						<div class="col-md-auto mb-3">
							<div class="card border border-black" style="width: 18rem;background-color: <?= $lib->setCardColor($d['status'])?>">
								<div class="card-header bg-light rounded-bottom-4 border-bottom border-black">
									<!-- <img src="../sepatu/gambar/<?= $d['gambar_sepatu']?>" alt="" class="card-img-top d-flex mx-auto" style="width: 50%; height: 50%"> -->
									<?php 
									$idSepatu = $d['id_sepatu'];
									$ids = $d['id_data_sepatu'];
									$warna = $d['warna'];
									/*if (isset($warna)) {
										$dataGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE id_sepatu=$idSepatu AND gambar_sepatu LIKE '%$warna%'");
									}else{
										$dataGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE id_sepatu=$idSepatu");
									}*/
									$dataWarna = mysqli_query($db->koneksi,"SELECT gambar_sepatu AS gs FROM data_sepatu INNER JOIN pesanan ON data_sepatu.id_data_sepatu=pesanan.id_data_sepatu WHERE data_sepatu.id_data_sepatu=$ids");
									$dg = mysqli_fetch_assoc($dataWarna);
									?>
									<img src="/sepatu/owner/sepatu/gambar/<?= $dg['gs']?>" alt="" class="card-img-top d-flex mx-auto" style="width: 50%;">
								</div>
								<div class="card-body rounded">
									<h5 class="card-title"><?= $no++.". ".$d['username']?></h5>
									<div class="card-text text-truncate" title="<?= $d['merk_sepatu']." ".$d['nama_sepatu']; ?>"><?= $d['merk_sepatu']." ".$d['nama_sepatu']; ?></div>
									<div class="card-text">Varian : <?= $d['warna'].', '.$d['ukuran']?></div>
									<div class="card-text">Jumlah : <?= $d['jumlah_beli']?></div>
									<div class="card-text">Total : <?= rupiah($d['total_biaya']) ?></div>
									<div class="card-text">Tanggal Expired : <?= $d['tgl_exp'] ?></div>
									<div class="card-text">Status : <?= $d['status'] ?></div>
									<div class="card-text text-end mt-2">
										<?= $lib->btnAction($d['status'],$d['id_pesan'],$d['tgl_exp'],$d['id_sepatu']) ?>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
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
</body>
</html>