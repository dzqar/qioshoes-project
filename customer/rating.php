<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../koneksi.php';

$idPesan = $_POST['id_pesan'];

$tampilDaftarPesanan = $p->tampilDaftarPesanan("WHERE id_pesan='$idPesan'");

  // Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
	header('location: /sepatu/form/login.php?pesan=belum_login');
	exit;
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
	<!-- Fontawesome -->
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/FONTAWESOME/css/all.min.css">
	<link rel="stylesheet" href="styleRating/style.css">
	<script src="styleRating/script.js" defer></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="/sepatu/style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/sweetalert2.min.css">
	<link rel="stylesheet" href="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/css/animate.min.css">
	<script src="/sepatu/style/FR FRONTEND/SWEETALERT2/assets/js/sweetalert2.min.js"></script>
	<title>HALAMAN RATING</title>
</head>
<body>
	<!-- Header -->
	<?php $lib->navCust('','','') ?>

	<section>
		<div class="container-fluid mb-3">
			<form action="proses.php" method="POST">
				<?php foreach($tampilDaftarPesanan as $d){ ?>
					<input type="number" name="id_sepatu" value="<?= $d['id_sepatu']?>" hidden>
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

						<!-- Rating -->
						<div class="row justify-content-center my-2">
							<div class="col text-center">
								<div class="stars">
									<input type="radio" id="star5" name="rating" value="1" style="display: none;" />
									<label for="star5" class="star ms-3">
										<i class="fa-solid fa-star"></i>
									</label>
									<input type="radio" id="star4" name="rating" value="2" style="display: none;" />
									<label for="star4" class="star ms-3">
										<i class="fa-solid fa-star"></i>
									</label>
									<input type="radio" id="star3" name="rating" value="3" style="display: none;" />
									<label for="star3" class="star ms-3">
										<i class="fa-solid fa-star"></i>
									</label>
									<input type="radio" id="star2" name="rating" value="4" style="display: none;" />
									<label for="star2" class="star ms-3">
										<i class="fa-solid fa-star"></i>
									</label>
									<input type="radio" id="star1" name="rating" value="5" style="display: none;" />
									<label for="star1" class="star ms-3">
										<i class="fa-solid fa-star"></i>
									</label>
								</div>
							</div>
						</div>

						<!-- Ulasan -->
						<div class="row justify-content-center">
							<textarea name="ulasan" placeholder="Umpan Balik" cols="10" rows="3" class="form-control w-50 border border-black"></textarea>
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
								<button type="submit" name="btn" value="ratePesanan" class="rounded-4 btn" style="
								--bs-btn-color: #fff;
								--bs-btn-bg: #3e3232;
								--bs-btn-hover-color: black;
								--bs-btn-hover-bg: #fff;
								--bs-btn-hover-border-color: #3e3232;
								">Rate</button>
							</div>
						</div>
					</div>
				<?php } ?>
			</form>
		</div>
	</section>

	<!-- Footer -->
	<?php $lib->footer(); ?>
</body>
</html>