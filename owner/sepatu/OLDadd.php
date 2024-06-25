<?php
  // Memulai session dari login
session_start();

  // Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

  // Mengambil semua code yang ada di koneksi.php
include '../../koneksi.php';

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
	<title>Data Sepatu - Owner</title>
</head>
<body>
	<script>
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
                    preview.src = "#";
                    preview.style.display= "block";
                }
            }
        </script>
        <div class="container-fluid">
        	<div class="row">
        		<!-- Sidebar -->
        		<?php $lib->sidebar() ?>
        		<div class="col p-3 min-vh-100">
        			<form action="proses.php" enctype="multipart/form-data" method="POST">
        				<!-- Gambar Sepatu -->
        				<div class="row justify-content-center border border-black bg-light mb-3">
        					<img src="#" id="preview" alt="gambar sepatu" class="img-fluid" style="width: 30%">
        				</div>

        				<div class="container">
        					<!-- Tombol buat gambar -->
        					<div class="row justify-content-center mb-3">
							<!-- <div class="col text-end"><button class="btn btn-primary">Hapus Gambar</button></div>
								<div class="col"><button class="btn btn-primary">Ubah Gambar</button></div> -->
								<div class="col text-center">
									<input type="file" name="foto" id="foto" onchange="pripiw()" class="form-control visually-hidden" accept=".jpg,.png,.jpeg">
									<label for="foto" class="btn rounded-pill" style="
									--bs-btn-color: #fff;
									--bs-btn-bg: #3e3232;
									--bs-btn-hover-color: black;
									--bs-btn-hover-bg: #fff;
									--bs-btn-hover-border-color: #3e3232;
									">
									Tambah Gambar
								</label>
							</div>
						</div>

						<!-- Merk & Nama Sepatu -->
						<div class="row justify-content-center mb-3">
							<div class="col-4 text-end"><input type="text" class="form-control-lg border border-black" value="Converse" readonly></div>
							<div class="col-4"><input type="text" class="form-control-lg border border-black" name="nama_sepatu" placeholder="Nama Sepatu"></div>
						</div>

						<!-- Deskripsi -->
						<div class="row justify-content-center">
							<textarea name="deskripsi" cols="20" rows="2" class="form-control w-50 border border-black" placeholder="Tambahkan deskripsi..."></textarea>
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
								<button type="submit" value="TambahSepatu" name="btn" class="rounded-4 btn text-wrap" style="
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
		</div>
	</div>
</body>
</html>