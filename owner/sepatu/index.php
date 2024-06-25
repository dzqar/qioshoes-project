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

$tampilSepatu = $gc->tampilSepatu("*","LIMIT $start,$limit");

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
		<?php include '../../script/pesan.js' ?>
	</script>
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<?php $lib->sidebar(); ?>
			<!-- content -->
			<div class="col p-3 min-vh-100">
				<!-- <h2 class="text-center">Data Sepatu <button onclick="window.location.href='add.php'" class="btn bi bi-plus-square-fill fs-2" style="
				--bs-btn-color: #3e3232;
				/*--bs-btn-bg: #3e3232;*/
				--bs-btn-hover-color: #927979;
				/*--bs-btn-hover-bg: #3e3232;*/
				"></button></h2> -->
				<h2 class="text-center">Data Sepatu <button type="button" data-bs-toggle="modal" data-bs-target="#jmlhdata" class="btn bi bi-plus-square-fill fs-2" style="
				--bs-btn-color: #3e3232;
				/*--bs-btn-bg: #3e3232;*/
				--bs-btn-hover-color: #927979;
				/*--bs-btn-hover-bg: #3e3232;*/
				"></button></h2>
				<div class="row justify-content-center mx-auto">
					<?php 
					foreach ($tampilSepatu as $d) {
						?>
						<div class="col-md-auto mb-3">
							<div class="card bg-secondary-subtle border border-black" style="width: 18rem;">
								<div class="card-header bg-light rounded-bottom-4 border-bottom border-black">
									<img src="gambar/<?= $d['gambar_sepatu']?>" alt="" class="card-img-top d-flex mx-auto" style="width: 50%">
								</div>
								<div class="card-body rounded">
									<h5 class="card-title"><?= $d['merk_sepatu']?></h5>
									<div class="card-text"><?= $d['nama_sepatu']?></div>
									<div class="card-text">
										<a href="varian/?nama_sepatu=<?= $d['nama_sepatu']?>" class="text-decoration-none">Lihat varian
											<?php
											$idSepatu = $d['id_sepatu'];
											$dataStok = mysqli_query($db->koneksi,"SELECT stok FROM data_sepatu WHERE id_sepatu='$idSepatu' AND stok <= 3");
											if (mysqli_num_rows($dataStok) > 0){ ?>
											<span class="badge rounded-pill bg-danger">
												<?php echo mysqli_num_rows($dataStok) ?>
											</span>
										<?php } ?>
										</a>
									</div>
									<div class="card-text text-end">
										<form action="edit.php" method="POST" class="my-2">
											<a href="proses.php?id_sepatu=<?= $d['id_sepatu']?>&btn=HapusSepatu" class="rounded-pill btn" style="
											--bs-btn-color: #fff;
											--bs-btn-bg: #3e3232;
											--bs-btn-hover-color: #fff;
											--bs-btn-hover-bg: var(--bs-red);
											--bs-btn-hover-border-color: #3e3232;
											" onclick="return confirm('Apakah anda yakin ingin menghapus sepatu ini?')">Hapus</a>
											<input type="hidden" name="nama_sepatu" value="<?= $d['nama_sepatu']?>">
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
					<?php } ?>
				</div>
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
									<input type="number" name="jum" size="1" class="form-control border-black" value="1" min="1" max="5"> 
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