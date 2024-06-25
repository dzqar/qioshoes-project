<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../../koneksi.php';

$idDataSepatu = $_POST['id_data_sepatu'];

$tampilEditDataSepatu = $s->tampilEditDataSepatu("data_sepatu.*,sepatu.merk_sepatu,sepatu.nama_sepatu","INNER JOIN sepatu ON data_sepatu.id_sepatu=sepatu.id_sepatu WHERE id_data_sepatu='$idDataSepatu'");

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
	<!-- jQuery -->
	<script src="/sepatu/script/jquery-3.7.1.min.js"></script>
	<script src="/sepatu/script/jquery.mask.min.js"></script>
	<title>Data Sepatu - Owner</title>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<?php
			$lib->sidebar();
			foreach ($tampilEditDataSepatu as $d) {
				?>
	<script type="text/javascript">
		function pripiw(){
		                  // Untuk menangkap tag dengan id="foto" dan return files nya itu index/utama (0) ketika sudah di upload
		                  var foto = document.getElementById("foto").files[0];
		                  // Menangkap tag dengan id="preview" untuk melakukan preview sebuah foto
		                  var preview = document.getElementById("preview");

		                  
		                  // Tanpa pake File API
		                  if (foto) {
		                    // Menambahkan atribut src pada tag yang id="preview" dengan valuenya ditangkap dari variabel foto
		                    preview.src = URL.createObjectURL(foto);
		                    // Membuat style id="preview" display="block" untuk di tampilkan
		                    preview.style.display = "block";
		                }else{
		                    // Jika tidak ada, maka src nya menjadi # dan display nya menjadi none, agar tidak di tampilkan
		                    preview.src = "/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>";
		                    preview.style.display= "block";
		                }
		            }

		            $(document).ready(function(){

		                // Format mata uang.
		                $( '#harga' ).mask('000.000.000', {reverse: true});

		            })

		        </script>
		        <div class="col p-3 min-vh-100">
		        	<form action="../proses.php" enctype="multipart/form-data" method="POST">
		        		<input type="number" name="id_data_sepatu" value="<?= $idDataSepatu?>" hidden>
		        		<input type="number" name="id_sepatu" value="<?= $d['id_sepatu']?>" hidden>
		        		<!-- Gambar Sepatu -->
		        		<div class="row justify-content-center border border-black bg-light mb-3">
		        			<img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>" id="preview" alt="gambar sepatu" class="img-fluid" style="width: 30%">
		        		</div>

		        		<div class="container">
		        			<!-- Tombol buat gambar -->
		        			<div class="row mb-3">
						<!-- <div class="col text-end mb-3"><button type="submit" name="btn" value="hapusGambar" class="btn rounded-pill" style="
						--bs-btn-color: #fff;
						--bs-btn-bg: #3e3232;
						--bs-btn-hover-color: black;
						--bs-btn-hover-bg: #fff;
						--bs-btn-hover-border-color: #3e3232;
						">Hapus Gambar</button></div> -->
						<!-- <div class="col"><button class="btn btn-primary">Ubah Gambar</button></div> -->
						<div class="col text-center">
							<input type="file" name="foto" id="foto" onchange="pripiw()" class="form-control visually-hidden" accept=".jpg,.png,.jpeg">
							<label for="foto" class="btn rounded-pill" style="
							--bs-btn-color: #fff;
							--bs-btn-bg: #3e3232;
							--bs-btn-hover-color: black;
							--bs-btn-hover-bg: #fff;
							--bs-btn-hover-border-color: #3e3232;
							">Ubah Gambar
						</label>
					</div>
				</div>

				<!-- Merk & Nama Sepatu -->
				<div class="row justify-content-center mb-3">
					<div class="col-4 text-end"><input type="text" name="merk_sepatu" class="form-control-lg border border-black" value="Converse" readonly></div>
					<div class="col-4"><input type="text" name="nama_sepatu" class="form-control-lg border border-black" value="<?= $d['nama_sepatu']?>" readonly></div>
				</div>

				<!-- Varian -->
				<div class="row mb-3">
					<div class="col">
						<div class="row justify-content-end">
							<div class="col-2">Stok</div>
							<div class="col-auto">:</div>
							<div class="col-auto mb-3"><input type="number" name="stok" value="<?= $d['stok']?>" min="0" class="form-control border border-black"></div>
						</div>
						<div class="row justify-content-end">
							<div class="col-2">Warna</div>
							<div class="col-auto">:</div>
							<div class="col-auto"><input type="text" name="warna" value="<?= $d['warna']?>" class="form-control border border-black"></div>	
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col-2">Harga</div>
							<div class="col-auto">:</div>
							<div class="col-auto mb-3"><input type="text" name="harga" id="harga" value="<?= $d['harga']?>" class="form-control border border-black"></div>
						</div>
						<div class="row">
							<div class="col-2">Ukuran</div>
							<div class="col-auto">:</div>
							<div class="col-auto"><input type="number" name="ukuran" value="<?= $d['ukuran']?>" class="form-control border border-black"></div>
						</div>
					</div>
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
						<button type="submit" value="EditVarianSepatu" name="btn" class="rounded-4 btn text-wrap" style="
						--bs-btn-color: #fff;
						--bs-btn-bg: #3e3232;
						--bs-btn-hover-color: black;
						--bs-btn-hover-bg: #fff;
						--bs-btn-hover-border-color: #3e3232;
						width: 100px;
						">Edit</button>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php } ?>
</div>
</div>
</body>
</html>