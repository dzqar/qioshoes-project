<?php
error_reporting(0);
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../../koneksi.php';
// $namaSepatu = $_POST['nama_sepatu'];
$namaSepatu = $_GET['nama_sepatu'];

$tampilDataSepatu = $s->tampilDataSepatu("*","WHERE nama_sepatu='$namaSepatu'");

// $namaSepatu = mysqli_fetch_assoc(mysqli_query($db->koneksi,"SELECT nama_sepatu FROM sepatu WHERE id_sepatu=$idSepatu"));

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
	<!-- Pesan -->
	<script>
		<?php include '../../../script/pesan.js' ?>
	</script>
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<?php $lib->sidebar(); ?>
			<!-- content -->
			<div class="col p-3 min-vh-100">
				<!-- <form action="add.php" method="POST"> -->
					<style>
						i.bi.bi-arrow-left:hover{
							color: #927979;
							/*font-weight: bold;*/
						}
					</style>
					<h2 class="text-center"><a href="../" style="color: #3e3232"><i class="bi bi-arrow-left"></i></a> Varian Sepatu "<?= $namaSepatu ?>"
						<button type="button" data-bs-toggle="modal" data-bs-target="#jmlhdata" class="btn bi bi-plus-square-fill fs-2" style="
						--bs-btn-color: #3e3232;
						/*--bs-btn-bg: #3e3232;*/
						--bs-btn-hover-color: #927979;
						/*--bs-btn-hover-bg: #3e3232;*/
						"></button>

					</h2>
				<!-- </form> -->


				<!-- Modal -->
				<div class="modal fade" id="jmlhdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<form method="post" action="add.php">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Input Jumlah Data Sepatu</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									Jumlah Data
									<input type="hidden" name="nama_sepatu" value="<?= $namaSepatu ?>">
									<input type="number" name="jum" size="1" class="form-control border-black" value="1" min="1" max="5" required> 
								</div>
								<div class="modal-footer">
									<button type="button" class="rounded-pill btn" style="
									--bs-btn-color: #fff;
									--bs-btn-bg: #3e3232;
									--bs-btn-hover-color: #fff;
									--bs-btn-hover-bg: var(--bs-red);
									--bs-btn-hover-border-color: #3e3232;
									" data-bs-dismiss="modal">Tutup</button>
									<button type="submit" class="rounded-pill btn" style="
									--bs-btn-color: #fff;
									--bs-btn-bg: #3e3232;
									--bs-btn-hover-color: black;
									--bs-btn-hover-bg: #fff;
									--bs-btn-hover-border-color: #3e3232;
									">Input</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="row justify-content-center mx-auto">
					<?php 
					if (isset($tampilDataSepatu)) {
						foreach ($tampilDataSepatu as $d) {
							?>
							<div class="col-md-auto mb-3">
								<div class="card border border-black" style="width: 18rem;background-color: <?= $lib->checkStok($d['stok'])?>">
									<div class="card-header bg-light border-bottom border-black rounded-bottom-4">
										<img src="../gambar/<?= $d['gambar_sepatu']?>" alt="" class="card-img-top d-flex mx-auto" style="width: 50%; height: 50%">
									</div>
									<div class="card-body rounded">
										<h4 class="card-title"><?= $d['merk_sepatu']?></h4>
										<div class="card-text"><?= $d['nama_sepatu']?></div>
										<div class="card-text">Ukuran : <?= $d['ukuran']?></div>
										<div class="card-text">Warna : <?= $d['warna']?></div>
										<div class="card-text">Stok : <?= $d['stok']?></div>
										<div class="card-text">Harga : Rp. <?= number_format($d['harga'],'0','','.')?></div>
										<div class="card-text text-end">
											<form action="edit.php" method="POST" class="my-2">
												<a onclick="if(confirm('Apakah anda yakin ingin menghapus varian sepatu ini?')){ window.location.href='../proses.php?id_data_sepatu=<?= $d['id_data_sepatu']?>&nama_sepatu=<?= $namaSepatu?>&btn=HapusVarianSepatu' }else{ return false }" class="rounded-pill btn" style="
												--bs-btn-color: #fff;
												--bs-btn-bg: #3e3232;
												--bs-btn-hover-color: #fff;
												--bs-btn-hover-bg: var(--bs-danger);
												--bs-btn-hover-border-color: #3e3232;
												">Hapus</a>
												<input type="hidden" name="id_data_sepatu" value="<?= $d['id_data_sepatu']?>">
												<input type="submit" value="Edit" class="rounded-pill btn" style="
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
							</div>
							<?php 
						} 
					}else{
						?>
						<div class="col-md-auto">
							<h4>Belum ada varian dari sepatu "<?= $namaSepatu?>"</h4>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>