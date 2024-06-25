<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../koneksi.php';

$tampilCust = $s->tampilCust();

// Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
	header('location: /sepatu/?pesan=tryAccess');
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
	<title>Dashboard - Owner</title>
</head>
<body>
  <!-- Pesan -->
  <script>
    <?php include '../script/pesan.js' ?>
  </script>
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<?php $lib->sidebar(); ?>
			<!-- content -->
			<div class="col p-3 min-vh-100">
				<h2 class="text-center">Dashboard</h2>
				<div class="row justify-content-center">
					<?php 
					foreach ($tampilCust as $d) {
						?>
						<div class="col-sm-3 mb-4">
							<div class="card" style="width: 18rem;">
								<div class="card-body">
									<h5 class="card-title"><?= $d['username']?></h5>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>