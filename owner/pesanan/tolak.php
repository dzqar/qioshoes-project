<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../koneksi.php';

$idPesan = $_POST['id_pesan'];

$tampilDaftarPesanan = $p->tampilDaftarPesanan("WHERE id_pesan='$idPesan'");

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
	<link rel="icon" href="/sepatu/style/logo/logo.png" type="image/x-icon">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.css">
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/icon/font/bootstrap-icons.min.css">
	<!-- Style Bootstrap -->
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/css/bootstrap.min.css">
	<script src="/sepatu/style/FR FRONTEND/BOOSTRAP/assets/js/bootstrap.bundle.min.js"></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="/sepatu/style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/sweetalert2.min.css">
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/animate.min.css">
	<script src="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/js/sweetalert2.min.js"></script>
	<title>Data Sepatu - Owner</title>
</head>
<body>
	<!-- <form action="proses.php" method="GET">
		<input type="number" name="id_pesan" value="<?= $_POST['id_pesan']?>">
		<textarea name="alasan" cols="20" rows="2"></textarea>
		<input type="submit" value="Tolak" name="btn">
	</form> -->
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<?php $lib->sidebar() ?>
			<div class="col p-3 min-vh-100">
				<form action="proses.php" method="POST" onsubmit="return confirm('Apakah anda yakin mau menolak pesanan ini?')">
					<?php foreach($tampilDaftarPesanan as $d){ ?>
						<input type="number" name="id_pesan" value="<?= $d['id_pesan']?>" hidden>
						<!-- Gambar Sepatu -->
						<div class="row justify-content-center border border-black bg-light">
							<img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>" alt="gambar sepatu" class="img-fluid" style="width: 30%">
						</div>

						<div class="container">
							<!-- Merk - Nama Sepatu -->
							<div class="row justify-content-center">
								<h1 class="text-center"><?= $d['merk_sepatu']." - ".$d['nama_sepatu']?></h1>
							</div>

							<!-- Varian -->
							<div class="row justify-content-center">
								<div class="col text-end fs-3">Varian : <?= $d['warna'].", ".$d['ukuran']?></div>
								<div class="col fs-3">Jumlah : <?= $d['jumlah_beli']?></div>
							</div>

							<!-- Total Biaya -->
							<div class="row justify-content-center">
								<div class="text-center fs-3">Total Biaya : <?= rupiah($d['total_biaya'])?></div>
							</div>

							<!-- Alasan Batal -->
							<div class="row justify-content-center">
								<textarea name="alasan" id="" cols="10" rows="3" class="form-control w-50 border border-black" placeholder="Alasan Batal"></textarea>
							</div>

							<!-- Tombol -->
							<div class="row justify-content-center">
								<div class="col text-end">
									<a onclick="history.back()" class="rounded-4 btn me-5" style="
									--bs-btn-color: #fff;
									--bs-btn-bg: #3e3232;
									--bs-btn-hover-color: black;
									--bs-btn-hover-bg: #fff;
									--bs-btn-hover-border-color: #3e3232;
									">Kembali</a>
									<!-- <button type="submit" class="btn btn-primary" name="btn" value="ratePesanan">Rate</button> -->
									<button type="submit" value="Tolak" name="btn" class="rounded-4 btn text-wrap" style="
									--bs-btn-color: #fff;
									--bs-btn-bg: #3e3232;
									--bs-btn-hover-color: black;
									--bs-btn-hover-bg: #fff;
									--bs-btn-hover-border-color: #3e3232;
									width: 100px;
									">Tolak Pesanan</button>
								</div>
							</div>
						</div>
					<?php } ?>
				</form>
			</div>
		</div>
	</body>
	</html>