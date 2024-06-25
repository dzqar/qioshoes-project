<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../../koneksi.php';

$namaSepatu = $_POST['nama_sepatu'];
$jum = $_POST['jum'];

$tampilEditSepatu = $s->tampilEditSepatu($namaSepatu);

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
			foreach ($tampilEditSepatu as $d) {
				$idSepatu = $d['id_sepatu'];
				$dataHarga = mysqli_query($db->koneksi,"SELECT COUNT(id_data_sepatu) AS ids, MAX(harga) AS harga FROM data_sepatu WHERE id_sepatu=$idSepatu");
				$dh = mysqli_fetch_assoc($dataHarga);
				function hargaValue(){
					global $dh;
					$harga = $dh['harga'];
					if ($dh['ids'] > 0) {
						return "value='$harga'";
					}
				}
				?>
				<div class="col p-3 min-vh-100">
					<form action="../proses.php" enctype="multipart/form-data" method="POST">
						<input type="number" name="jum" value="<?= $jum?>" hidden>
						<?php 
						for ($i=1; $i <= $jum; $i++) { ?>
							<script type="text/javascript">
								function pripiw<?= $i?>(){
					                  // Untuk menangkap tag dengan id="foto" dan return files nya itu index/utama (0) ketika sudah di upload
					                  var foto = document.getElementById("foto<?= $i?>").files[0];
					                  // Menangkap tag dengan id="preview" untuk melakukan preview sebuah foto
					                  var preview = document.getElementById("preview<?= $i?>");

					                  
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
					                $( '#harga<?= $i?>' ).mask('000.000.000', {reverse: true});

					            })

					        </script>
					        <input type="number" name="id_sepatu[]" value="<?= $d['id_sepatu']?>" hidden>
					        <!-- Gambar Sepatu -->
					        <div class="row justify-content-center border border-black bg-light mb-3">
					        	<img src="/sepatu/owner/sepatu/gambar/<?= $d['gambar_sepatu']?>" id="preview<?= $i?>" alt="gambar sepatu" class="img-fluid" style="width: 30%">
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
							<input type="file" name="foto[]" id="foto<?= $i?>" onchange="pripiw<?= $i?>()" class="form-control visually-hidden" accept=".jpg,.png,.jpeg">
							<label for="foto<?= $i?>" class="btn rounded-pill" style="
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
					<div class="col-4 text-end"><input type="text" name="merk_sepatu[]" class="form-control-lg border border-black" value="Converse" readonly></div>
					<div class="col-4"><input type="text" name="nama_sepatu[]" class="form-control-lg border border-black" value="<?= $d['nama_sepatu']?>" readonly></div>
				</div>

				<!-- Varian -->
				<div class="row mb-3">
					<div class="col">
						<div class="row justify-content-end">
							<div class="col-2">Stok</div>
							<div class="col-auto">:</div>
							<div class="col-auto mb-3"><input type="number" name="stok[]" value="1" min="1" class="form-control border border-black"></div>
						</div>
						<div class="row justify-content-end">
							<div class="col-2">Warna</div>
							<div class="col-auto">:</div>
							<div class="col-auto"><input type="text" name="warna[]" class="form-control border border-black"></div>	
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col-2">Harga</div>
							<div class="col-auto">:</div>
							<div class="col-auto mb-3"><input type="text" name="harga[]" id="harga<?= $i?>" class="form-control border border-black" <?= hargaValue() ?>></div>
						</div>
						<div class="row">
							<div class="col-2">Ukuran</div>
							<div class="col-auto">:</div>
							<div class="col-auto"><input type="number" name="ukuran[]" class="form-control border border-black"></div>
						</div>
					</div>
				</div>
			<?php } ?>

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
					<button type="submit" value="TambahVarianSepatu" name="btn" class="rounded-4 btn text-wrap" style="
					--bs-btn-color: #fff;
					--bs-btn-bg: #3e3232;
					--bs-btn-hover-color: black;
					--bs-btn-hover-bg: #fff;
					--bs-btn-hover-border-color: #3e3232;
					width: 100px;
					">Tambah</button>
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