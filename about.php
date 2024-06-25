<?php 
session_start();

// Koneksi
include 'koneksi.php';
$id = (isset($_SESSION['id_user'])) ? $_SESSION['id_user'] : '0';
$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '0';
$role = (isset($_SESSION['role'])) ? $_SESSION['role'] : '0';

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
	<!-- Title -->
	<title>Beranda</title>
</head>
<body>
	<!-- Header -->
	<?php 
	if($username){
		if ($role == "Customer") {
			$lib->navCust('','','active'); 
		}elseif ($role == "Owner") {
			$lib->navOwner('active');
		}
	}else{
		$lib->navIndex(NULL); 
	}
	?>

	<!-- Awal Main -->
	<section id="main">
		<div class="container">
			<div class="row text-center mb-3">
				<div class="col">
					<!-- Biodata Perusahaan -->
					<img src="style/logo/logo.jpeg" width="150" height="150" class="mb-3 border border-2 rounded">
					<h2>QioShoes</h2>
					<p>Jalan Kemana, Neng</p>
				</div>
			</div>
			<div class="row justify-content-center mb-3">
				<div class="col">
					Selamat datang di QioShoes, destinasi utama Anda untuk sepatu berkualitas tinggi dari brand legendaris, Converse. Kami adalah pusat pilihan terbaik bagi pecinta sepatu yang menghargai gaya, kenyamanan, dan kualitas. Dibangun atas dedikasi kami terhadap industri alas kaki, kami dengan bangga menawarkan berbagai koleksi eksklusif dari Converse, dari klasik hingga yang terbaru, untuk memenuhi kebutuhan gaya hidup Anda.
				</div>
			</div>
			<div class="row justify-content-center mb-3">
				<div class="col">
					Di QioShoes, kami tidak hanya sekadar menjual sepatu; kami menciptakan pengalaman belanja yang tak terlupakan. Dengan tim ahli kami yang selalu siap membantu, kami berkomitmen untuk memberikan layanan pelanggan yang luar biasa, memastikan Anda mendapatkan sepatu yang sempurna sesuai dengan selera dan kebutuhan Anda.
				</div>
			</div>
			<div class="row justify-content-center mb-3">
				<div class="col">
					Setiap produk yang kami tawarkan telah dipilih dengan cermat untuk memastikan kualitasnya yang superior dan keasliannya sebagai produk Converse. Dari desain yang ikonik hingga material berkualitas tinggi, kami percaya bahwa setiap langkah kecil dalam proses pembelian Anda harus menjadi pengalaman yang menyenangkan dan memuaskan.
				</div>
			</div>
		</div>
	</section>
	<!-- Akhir Main -->
	
	<!-- Footer -->
	<?php $lib->footer(); ?>
</body>
</html>