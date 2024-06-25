<?php
// error_reporting(0);
/**
 * DATABASE
 */
class database {
	// Membuat variabel seperti pada file koneksi.php pada umumnya
	var $host = "localhost";
	var $username = "root";
	var $password = "";
	var $database = "ecomm";

	// Fungsi untuk membuat koneksi ke MySQL untuk menyambungkan database
	function __construct(){
		$this->koneksi = mysqli_connect($this->host,$this->username,$this->password,$this->database);
		if (mysqli_connect_errno()) {
			echo "Koneksi database gagal : ".mysqli_connect_error();
		}
	}
}
$db = new database();

/*
 * GLOBAL
 */
class globalic extends database {
	/*AWAL GLOBAL FUNCTION*/
	// Mendaftarkan akun calon customer
	function daftar($username, $password) {
		mysqli_query($this->koneksi,"INSERT INTO user values(NULL,1,'$username','$password')");
	}

	// Tampil sepatu
	function tampilSepatu($select,$kondisi) {
		$data = mysqli_query($this->koneksi,"SELECT $select FROM sepatu AS s $kondisi");
		while ($d = mysqli_fetch_array($data)){
			$hasil[] = $d;
		}
		return $hasil;
	}

	// function tampilDetailSepatu($id){
	// 	$data = mysqli_query($this->koneksi,"SELECT s.merk_sepatu,s.nama_sepatu,ds.* FROM sepatu AS s INNER JOIN data_sepatu AS ds ON s.id_sepatu=ds.id_sepatu WHERE s.id_sepatu='$id'");
	// 	while ($d = mysqli_fetch_array($data)) {
	// 		$hasil[] = $d;
	// 	}
	// 	return $hasil;
	// }

	// Menampilkan rata-rata rating
	function rataRating($id) {
		$data = mysqli_query($this->koneksi,"SELECT ulasan.id_sepatu,ROUND(AVG(rating),1) AS rataRating, COUNT(ulasan.id_user) AS jumlahUser FROM ulasan INNER JOIN user ON ulasan.id_user=user.id_user INNER JOIN pesanan ON ulasan.id_pesan=pesanan.id_pesan INNER JOIN data_sepatu ON pesanan.id_data_sepatu=data_sepatu.id_data_sepatu WHERE ulasan.id_sepatu='$id' GROUP BY ulasan.id_sepatu");
		while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
		}
		return $hasil;
	}

	//  Tampil semua ulasan berdasarkan id_buku
	function tampilRating($kondisi){
		$data = mysqli_query($this->koneksi,"SELECT * FROM ulasan $kondisi");
		while ($d = mysqli_fetch_array($data)) {
			$hasil[] = $d;
		}
		return $hasil;
	}
}
$gc = new globalic();

/*
 * SEPATU
 */
class sepatu extends database {
	/*AWAL OWNER*/
		// Tampil data customer
		function tampilCust() {
			$data = mysqli_query($this->koneksi,"SELECT * FROM user");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Tampil data sepatu
		function tampilDataSepatu($select,$where) {
			$data = mysqli_query($this->koneksi,"SELECT $select FROM sepatu INNER JOIN data_sepatu ON sepatu.id_sepatu=data_sepatu.id_sepatu $where ORDER BY ukuran DESC, warna ASC");
			while ($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}

		/*SEPATU*/
			// Tampil sepatu yang ingin diedit
			function tampilEditSepatu($namaSepatu) {
				$data = mysqli_query($this->koneksi,"SELECT * FROM sepatu WHERE nama_sepatu='$namaSepatu'");
				while ($d = mysqli_fetch_array($data)){
					$hasil[] = $d;
				}
				return $hasil;
			}

			// Tambah Sepatu
			function tambahSepatu($namaSepatu,$gambarSepatu,$deskripsi) {
				mysqli_query($this->koneksi,"INSERT INTO sepatu VALUES(NULL,'Converse','$namaSepatu','$gambarSepatu','$deskripsi')");
			}

			// Edit Sepatu
			function editSepatu($id,$namaSepatu,$more) {
				mysqli_query($this->koneksi,"UPDATE sepatu SET nama_sepatu='$namaSepatu'$more WHERE id_sepatu='$id'");
			}

			// Hapus Sepatu
			function hapusSepatu($id){
				mysqli_query($this->koneksi,"DELETE FROM sepatu WHERE id_sepatu='$id'");
				mysqli_query($this->koneksi,"DELETE FROM data_sepatu WHERE id_sepatu='$id'");
			}

			// Edit Sepatu
			function hapusGambarSepatu($id) {
				mysqli_query($this->koneksi,"UPDATE sepatu SET gambar_sepatu='' WHERE id_sepatu='$id'");
			}

		/*VARIAN*/
			// Tampil edit data sepatu
			function tampilEditDataSepatu($select,$where) {
				$data = mysqli_query($this->koneksi,"SELECT $select FROM data_sepatu $where");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}

			// Edit data sepatu
			function editDataSepatu($idDataSepatu,$stok,$ukuranSepatu,$warna,$harga,$gambarSepatu) {
				mysqli_query($this->koneksi,"UPDATE data_sepatu SET ukuran='$ukuranSepatu', warna='$warna', stok='$stok', harga='$harga'$gambarSepatu WHERE id_data_sepatu='$idDataSepatu'");
			}

			// Tambah Data Sepatu
			function tambahDataSepatu($idSepatu,$stok,$ukuran,$warna,$harga,$gambarSepatu) {
				mysqli_query($this->koneksi,"INSERT INTO data_sepatu VALUES(NULL,'$idSepatu','$ukuran','$warna','$stok','$harga','$gambarSepatu')");
			}

			// Hapus Data Sepatu
			function hapusDataSepatu($idDataSepatu) {
				mysqli_query($this->koneksi,"DELETE FROM data_sepatu WHERE id_data_sepatu='$idDataSepatu'");
			}

			// Tambah Stok Data Sepatu
			function tambahStokDataSepatu($id,$stok){
				mysqli_query($this->koneksi,"UPDATE data_sepatu SET stok = stok + $stok WHERE id_data_sepatu='$id'");
			}
	/*AKHIR OWNER*/

	/*AWAL CUSTOMER*/
		/*AWAL SEPATU*/
			// Proses Beli Sepatu
			function beliSepatu($idSepatu,$kode,$ukuran,$warna,$jumlahBeli,$idUser,$total){
				// session_start();
				$data = mysqli_query($this->koneksi,"SELECT id_data_sepatu,ukuran,warna,stok,harga FROM data_sepatu WHERE ukuran='$ukuran' AND warna='$warna' AND id_sepatu='$idSepatu'");
				$d = mysqli_fetch_assoc($data);
				$ids = $d['id_data_sepatu'];

				if ($d['stok'] <= 0) {
					$_SESSION['idSepatu'] = $idSepatu;
					header("location:detail.php?pesan=stokSepatuHabis");
				}elseif ($jumlahBeli > $d['stok']) {
					$_SESSION['idSepatu'] = $idSepatu;
					$_SESSION['stokSepatu'] = $d['stok'];
					header("location:detail.php?pesan=stokSepatuTidakCukup");
				}else{
					$date = date('Y-m-d');
					$tglExp = date('Y-m-d', strtotime($date.' +1 week'));
					mysqli_query($this->koneksi,"INSERT INTO pesanan VALUES(NULL,'$kode','$idUser','$ids','$jumlahBeli',NOW(),'$tglExp','1','','$total')");
					header("location:./pesanan.php?pesan=berhasilBeliSepatu");
				}
			}
		/*AKHIR SEPATU*/
	/*AKHIR CUSTOMER*/
}
$s = new sepatu();

/**
 * PESANAN
 */
class pesanan extends database {
	/*AWAL PESANAN*/
		// Tampil daftar pesanan
		function tampilDaftarPesanan($kondisi){
			$data = mysqli_query($this->koneksi,"SELECT * FROM (((pesanan INNER JOIN user ON pesanan.id_user=user.id_user) INNER JOIN data_sepatu ON pesanan.id_data_sepatu=data_sepatu.id_data_sepatu) INNER JOIN sepatu ON data_sepatu.id_sepatu=sepatu.id_sepatu) $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Terima
		function terimaPesanan($id){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=2 WHERE id_pesan='$id'");
		}
		// Siap Diambil
		function siapDiambilPesanan($id){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=3 WHERE id_pesan='$id'");
		}
		// Selesaikan
		function selesaikanPesanan($id){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=4 WHERE id_pesan='$id'");
		}
		// Tolak
		function tolakPesanan($id,$alasan){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=6, alasan='$alasan' WHERE id_pesan='$id'");
		}
		// Tolak Kadaluarsa
		function kadaluarsaPesanan($id){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=7, alasan='Anda sudah melewati batas waktu pengambilan ! ! !' WHERE id_pesan='$id'");
		}
		// Batal
		function batalPesanan($id,$alasan){
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=5, alasan='$alasan' WHERE id_pesan='$id'");
		}
	/*AKHIR PESANAN*/

	/*AWAL RATING*/
		// Rating
		function ratePesanan($idUser,$idSepatu,$ulasan,$rating,$idPesan){
			mysqli_query($this->koneksi,"INSERT INTO ulasan VALUES(NULL,'$idPesan','$idUser','$idSepatu','$ulasan','$rating',NOW())");
			mysqli_query($this->koneksi,"UPDATE pesanan SET status=8 WHERE id_pesan='$idPesan'");
		}
	/*AKHIR RATING*/
}
$p = new pesanan();

class lib {
	function setCardColor(string $status): string {
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
	}

	function checkStok($stok) {
		if ($stok <= 3) {
	      return '#FF8C8C';
		}else{
	      return '#D9D9D9';
		}
	}

	function btnAction($status,$idPesan,$exp,$idSepatu) {
	    switch ($status) {
	        case 'Belum Diterima':
	        $tgl = date('Y-m-d');
	        if ($tgl === $exp) {
		        return '<form action="/sepatu/customer/download.php" method="POST" class="d-inline" target="_blank">
	                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
	                        <input type="submit" name="btn" value="Cek Bukti" class="rounded-pill btn" style="
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  ">
	                      </form>
	                      <form action="proses.php" method="POST" class="d-inline">
	                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
	                        <button type="submit" name="btn" value="TolakKadaluarsa" class="btn btn-danger rounded-pill" name="btn">Tolak</button>
	                      </form>
	                      <a onclick="'."window.location.href='proses.php?btn=Terima&id_pesan=$idPesan'".'" class="btn btn-success">Terima</a>';
	        }else{
		        return '<form action="/sepatu/customer/download.php" method="POST" class="d-inline" target="_blank">
	                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
	                        <input type="submit" name="btn" value="Cek Bukti" class="rounded-pill btn" style="
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  ">
	                      </form>
	                      <form action="tolak.php" method="POST" class="d-inline">
	                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
	                        <input type="submit" name="btn" value="Tolak" class="btn btn-danger rounded-pill">
	                      </form>
	                      <a onclick="'."window.location.href='proses.php?btn=Terima&id_pesan=$idPesan'".'" class="btn btn-success rounded-pill">Terima</a>';
            }
	        case 'Dikemas':
	        return '<form action="/sepatu/customer/download.php" method="POST" class="d-inline" target="_blank">
                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
                        <input type="submit" name="btn" value="Cek Bukti" class="rounded-pill btn" style="
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  ">
                    <a onclick="'."window.location.href='proses.php?btn=siapDiambil&id_pesan=$idPesan'".'" class="btn btn-success rounded-pill">Siap Diambil</a>';
	        case 'Siap Diambil':
	        return '<form action="/sepatu/customer/download.php" method="POST" class="d-inline" target="_blank">
                        <input type="number" name="id_pesan" value="'.$idPesan.'" hidden>
                        <input type="submit" name="btn" value="Cek Bukti" class="rounded-pill btn" style="
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  ">
                      </form>
	        <a onclick="'."window.location.href='proses.php?btn=selesaikanPesanan&id_pesan=$idPesan'".'" class="btn btn-success rounded-pill">Selesaikan</a>';
	        case 'Selesai':
	        return "<form action='/sepatu/customer/download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>";
	        // <input type='number' name='id_pesan' value='$idPesan' hidden><input type='submit' name='btn' value='Beri Penilaian' class='btn btn-success' />
	        case 'Penilaian':
	        // return "<a href='proses.php?' class='btn btn-success rounded-pill'>Lihat</a>";
	        return "<form action='detail_rating.php' method='POST' class='d-inline'>
                        <input type='number' name='id_sepatu' value='$idSepatu' hidden>
                        <button type='submit' name='btn' value='lihatUlasan' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>Lihat</button>
                      </form>";
	        default:
	        // return "<a href='proses.php?' class='btn btn-success'>Cek Bukti</a>";
	        return "<form action='/sepatu/customer/download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>";
	    }
	}

	function btnBatal($idUser,$status,$idPesan,$idSepatu,$cekRate) {
	    switch ($status) {
	        case 'Belum Diterima':
	        return "<form action='download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>
                      <form action='tolak.php' method='POST' class='d-inline'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Batal' class='btn btn-danger rounded-pill'>
                      </form>";
	        case 'Dikemas':
	        return "
			<form action='download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>
	        <form action='tolak.php' method='POST' class='d-inline'>
	        <input type='number' name='id_pesan' value='$idPesan' hidden><input type='submit' name='btn' value='Batal' class='btn btn-danger rounded-pill' />
	        </form>";
	        case 'Siap Diambil':
	        return "<form action='download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>
	        ";
	        // <a href='proses.php?btn=selesaikanPesanan&id_pesan=$idPesan' class='btn btn-success rounded-pill'>Selesaikan</a>
	        case 'Selesai':
	        if ($cekRate <= 0) {
	        	// Kalo belum pernah rating sepatu nya samsek, maka bisa memberikan ulasan
		        return "
		        <form action='download.php' method='POST' class='d-inline' target='_blank'>
	                        <input type='number' name='id_pesan' value='$idPesan' hidden>
	                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
	                  --bs-btn-color: #fff;
	                  --bs-btn-bg: #3e3232;
	                  --bs-btn-hover-color: black;
	                    --bs-btn-hover-bg: #fff;
	                      --bs-btn-hover-border-color: #3e3232;
	                  '>
	                      </form>
		        <form action='rating.php' method='POST' class='d-inline'>
		        <input type='number' name='id_pesan' value='$idPesan' hidden><input type='submit' name='btn' value='Beri Penilaian' class='btn btn-success rounded-pill' />
		        </form>";
	        }else{
	        	// Kalo udh pernah rating sepatunya, maka tidak bisa memberi ulasan lagi
		        return "
		        <form action='download.php' method='POST' class='d-inline' target='_blank'>
	                        <input type='number' name='id_pesan' value='$idPesan' hidden>
	                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
	                  --bs-btn-color: #fff;
	                  --bs-btn-bg: #3e3232;
	                  --bs-btn-hover-color: black;
	                    --bs-btn-hover-bg: #fff;
	                      --bs-btn-hover-border-color: #3e3232;
	                  '>
	                  </form>
	            <form action='proses.php' method='POST' class='d-inline'>
                        <input type='number' name='id_sepatu' value='$idSepatu' hidden>
                        <input type='number' name='id_user' value='$idUser' hidden>
                        <button type='submit' name='btn' value='lihatUlasan' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>Lihat Ulasan Anda</button>
                      </form>";
	        }
	        case "Penilaian":
	        /*return "<a href='proses.php?' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>Lihat</a>";*/
            return "<form action='proses.php' method='POST' class='d-inline'>
                        <input type='number' name='id_sepatu' value='$idSepatu' hidden>
                        <button type='submit' name='btn' value='lihatUlasan' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>Lihat</button>
                      </form>";
	        default:
	        /*return "<a href='proses.php?' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>Cek Bukti</a>";*/
            return "<form action='download.php' method='POST' class='d-inline' target='_blank'>
                        <input type='number' name='id_pesan' value='$idPesan' hidden>
                        <input type='submit' name='btn' value='Cek Bukti' class='rounded-pill btn' style='
                  --bs-btn-color: #fff;
                  --bs-btn-bg: #3e3232;
                  --bs-btn-hover-color: black;
                    --bs-btn-hover-bg: #fff;
                      --bs-btn-hover-border-color: #3e3232;
                  '>
                      </form>";
	    }
	}

	function navIndex($idSepatu){
		global $username;
		function linkz($username){
			switch ($username) {
				case NULL:
				case 'NULL':
				case 0:
				case '0':
					echo "/"."sepatu"."/";
					break;
				
				default:
					echo "./";
					break;
			}
		}
		?>
		<nav class="navbar navbar-expand-lg bg-white fixed-top px-5">
	      <div class="container-fluid">
	        <a class="navbar-brand" onclick="window.location.href='<?php linkz($username)?>'">
	        	<img src="/sepatu/style/logo/logo.jpeg" alt="" style="width: 2.35em" class="me-2 rounded">
	        QioShoes
	    </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	          <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
	          <ul class="navbar-nav">
	            <li class="nav-item me-3">
	              <!-- <a class="nav-link" aria-current="page" href="/sepatu/form/login.php">Masuk</a> -->
	              <a class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</a>
	            </li>
	            <li class="nav-item me-3">
	              <!-- <a class="nav-link" href="/sepatu/form/daftar.php">Daftar</a> -->
	              <a class="nav-link" data-bs-toggle="modal" data-bs-target="#daftarModal">Daftar</a>
	            </li>
	            <li class="nav-item">
	              <a class="nav-link" onclick="window.location.href='/sepatu/about.php'">Tentang Kami</a>
	            </li>
	          </ul>
	        </div>
	      </div>
	    </nav>

	    <!-- Modal Login -->
                      <form action="/sepatu/form/proses.php" method="POST">
                        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="--bs-modal-bg: #3e3232; --bs-modal-color: #fff; --bs-modal-border-color: #fff;">
                              <div class="modal-header justify-content-center text-center">
                                <h1 class="modal-title fs-1" id="exampleModalLabel">Masuk</h1>
                                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                              </div>
                              <div class="modal-body text-start">
                              	<?php if (isset($_SESSION['auth']) && $_SESSION['auth'] >= 3) { ?>
	                              	<input type="number" name="id_sepatu" value="<?= $idSepatu?>" hidden>
	                                <label for="username" class="form-label">Username</label>
	                                <input type="text" name="username" id="username" class="form-control mb-3" placeholder="Anda sudah tidak bisa masuk kembali !" disabled>

	                                <label for="pw" class="form-label">Password</label>
	                                <input type="password" name="password" id="pw" class="form-control" placeholder="Anda sudah tidak bisa masuk kembali !" disabled>
                            	<?php }else{ ?>
	                              	<input type="number" name="id_sepatu" value="<?= $idSepatu?>" hidden>
	                                <label for="username" class="form-label">Username</label>
	                                <input type="text" name="username" id="username" class="form-control mb-3">

	                                <label for="pw" class="form-label">Password</label>
	                                <input type="password" name="password" id="pw" class="form-control">
                            	<?php } ?>
                              </div>
                              <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->

                                <div class="col">
                                  <a data-bs-toggle="modal" data-bs-target="#daftarModal" class="btn" style="
                                  --bs-btn-color: #fff;
                                  --bs-btn-hover-border-color: #fff;
                                  --bs-btn-hover-color: #fff;
                                  ">Daftar</a>
                                </div>
                                <div class="col text-end">
                                	<?php if (isset($_SESSION['auth']) && $_SESSION['auth'] >= 3) { ?>
                                  <button role="button" type="submit" name="btn" value="Login" class="btn" disabled style="
                                  --bs-btn-color: #fff;
                                  --bs-btn-hover-border-color: #fff;
                                  --bs-btn-hover-color: #fff;
                                  --bs-btn-disabled-border-color: var(--bs-white);
                                  --bs-btn-disabled-color: var(--bs-white);
                                  --bs-btn-disabled-bg: var(--bs-danger);
                                  ">Login</button>
                            	<?php }else{ ?>
                                  <button role="button" type="submit" name="btn" value="Login" class="btn" style="
                                  --bs-btn-color: #fff;
                                  --bs-btn-hover-border-color: #fff;
                                  --bs-btn-hover-color: #fff;
                                  ">Login</button>
                              <?php } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>

                      <!-- Modal Daftar -->
                      <form action="/sepatu/form/proses.php" method="POST">
                        <div class="modal fade" id="daftarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="--bs-modal-bg: #d9d9d9; --bs-modal-color: #000; --bs-modal-border-color: #000;">
                              <div class="modal-header justify-content-center text-center border-bottom border-black">
                                <h1 class="modal-title fs-1" id="exampleModalLabel">Daftar</h1>
                                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                              </div>
                              <div class="modal-body text-start border-bottom border-black">
                              	<input type="number" name="id_sepatu" value="<?= $idSepatu?>" hidden>
                                <label for="exampleInputtext1" class="form-label">Username</label>
                                <input type="text" class="form-control border border-black mb-3" name="username" placeholder="Masukkan username anda" >
                                <!-- pattern=".{3,25}" required -->

                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <!-- Pake Icon Mata -->
                                <div class="input-group mb-3" id="pw">
                                  <input type="password" name="password" class="form-control border border-black" placeholder="Masukkan password anda" >
                                  <!-- pattern=".{8,25}" required title="Password minimal 8 karakter, dan maksimal 20 karakter" -->
                                  <div class="input-group-text border border-black">
                                    <a href="" style="color: #333"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->

                                <div class="col">
                                  <a data-bs-toggle="modal" data-bs-target="#loginModal" class="btn" style="
                                  --bs-btn-color: #000;
                                  --bs-btn-hover-border-color: #000;
                                  --bs-btn-hover-color: #000;
                                  ">Masuk</a>
                                </div>
                                <div class="col text-end">
                                  <button role="button" type="submit" name="btn" value="Daftar" class="btn" style="
                                  --bs-btn-color: #000;
                                  --bs-btn-hover-border-color: #000;
                                  --bs-btn-hover-color: #000;
                                  ">Buat Akun</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
<?php
	}

	function navCust($beranda,$pesanan,$about){
		global $username;
		function linkz($username){
			switch ($username) {
				case NULL:
				case 'NULL':
				case 0:
				case '0':
					echo "/"."sepatu"."/";
					break;
				
				default:
					echo "./";
					break;
			}
		}
		?>
		<nav class="navbar navbar-expand-lg bg-white fixed-top px-5">
	      <div class="container-fluid">
	        <a class="navbar-brand" onclick="window.location.href='<?php linkz($username) ?>'">
	        	<img src="/sepatu/style/logo/logo.jpeg" alt="" style="width: 2.35em" class="me-2 rounded">
	        QioShoes
	    </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	          <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
	          <ul class="navbar-nav">
	            <li class="nav-item dropdown">
		          <button class="nav-link text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            <img src='/sepatu/style/img/profile.png' alt='' width='40px' height='40px' class='rounded-circle me-2' />
		          </button>
		          <ul class="dropdown-menu dropdown-menu-end text-end">
		            <li><a class="dropdown-item" href="#"><?= $username?></a></li>
		          	<li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item <?= $beranda?>" onclick="window.location.href='/sepatu/customer/'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Beranda</a></li>
		            <li><a class="dropdown-item <?= $pesanan?>" onclick="window.location.href='/sepatu/customer/pesanan.php'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Daftar Pesanan</a></li>
		            <li><a class="dropdown-item <?= $about?>" onclick="window.location.href='/sepatu/about.php'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Tentang Kami</a></li>
		            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/logout.php'" style="
		            --bs-dropdown-link-hover-color: var(--bs-danger);
		            --bs-dropdown-link-active-bg: #d9d9d9;
		            --bs-dropdown-link-active-color: var(--bs-danger);
		            ">Keluar</a></li>
		          </ul>
		        </li>
	          </ul>
	        </div>
	      </div>
	    </nav>
<?php
	}

	function navOwner($about){
		global $username;
		function linkowner($username){
			switch ($username) {
				case NULL:
				case 'NULL':
				case 0:
				case '0':
					echo "/"."sepatu"."/";
					break;
				
				default:
					echo "./";
					break;
			}
		}
		?>
		<nav class="navbar navbar-expand-lg bg-white fixed-top px-5">
	      <div class="container-fluid">
	        <a class="navbar-brand" onclick="window.location.href='<?php linkowner($username) ?>'">
	        	<img src="/sepatu/style/logo/logo.jpeg" alt="" style="width: 2.35em" class="me-2 rounded">
	        QioShoes
	    </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	          <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
	          <ul class="navbar-nav">
	            <li class="nav-item dropdown">
		          <button class="nav-link text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            <img src='/sepatu/style/img/profile.png' alt='' width='40px' height='40px' class='rounded-circle me-2' />
		          </button>
		          <ul class="dropdown-menu dropdown-menu-end text-end">
		            <li><a class="dropdown-item" href="#"><?= $username?></a></li>
		          	<li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/owner/'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Dashboard</a></li>
		            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/owner/pesanan/'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Daftar Pesanan</a></li>
		            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/owner/sepatu/'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Data Sepatu</a></li>
		            <li><a class="dropdown-item <?= $about?>" onclick="window.location.href='/sepatu/about.php'" style="
		            --bs-dropdown-link-hover-bg: #927979;
		            --bs-dropdown-link-hover-color: #fff;
		            --bs-dropdown-link-active-bg: #3e3232;
		            --bs-dropdown-link-active-color: #fff;
		            ">Tentang Kami</a></li>
		            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/logout.php'" style="
		            --bs-dropdown-link-hover-color: var(--bs-danger);
		            --bs-dropdown-link-active-bg: #d9d9d9;
		            --bs-dropdown-link-active-color: var(--bs-danger);
		            ">Keluar</a></li>
		          </ul>
		        </li>
	          </ul>
	        </div>
	      </div>
	    </nav>
<?php
	}

	// Sidebar
	function sidebar(){
		global $username;
		?>
		<nav class="col-sm-auto sticky-top" style="background-color: #3E3232">
				<div class="d-flex flex-sm-column flex-row flex-nowrap align-items-center sticky-top" style="background-color: #3E3232">
					<a onclick="window.location.href='/sepatu/owner/'" class="d-block p-3 link-light text-decoration-none" title="Yshoes" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Icon-only">
						<!-- <i class="bi-bootstrap fs-1 text-white"></i> -->
						<!-- <img src="../style/logo/ds.png" alt="icon"> -->
						<img src="/sepatu/style/logo/logo.jpeg" alt="icon" style="width: 4.15em" class="rounded-4">
					</a>
					<ul class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center justify-content-between w-100 px-3 align-items-center">
						<li class="nav-item">
							<a onclick="window.location.href='/sepatu/owner/'" class="nav-link py-3 px-2" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
								<i class="bi-house fs-1 text-white"></i>
							</a>
						</li>
						<li>
							<a onclick="window.location.href='/sepatu/owner/pesanan/'" class="nav-link py-3 px-2" title="Daftar Pesanan" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Daftar Pesanan">
								<i class="bi bi-cart-fill fs-1 text-white"></i>
							</a>
						</li>
						<li>
							<a onclick="window.location.href='/sepatu/owner/sepatu/'" class="nav-link py-3 px-2" title="Data Sepatu" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Data Sepatu">
								<i class="bi bi-clipboard2-fill fs-1 text-white"></i>
							</a>
						</li>
					</ul>
					<div class="dropdown">
						<a href="#" class="d-flex align-items-center justify-content-center p-3 link-light text-decoration-none dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src='/sepatu/style/img/profile.png' alt='' width='40px' height='40px' class='rounded-circle me-2' />
						</a>
						<ul class="dropdown-menu text-small shadow">
				            <li><a class="dropdown-item" href="#"><?= $username?></a></li>
				          	<li><hr class="dropdown-divider"></li>
				            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/about.php'" style="
				            --bs-dropdown-link-hover-bg: #927979;
				            --bs-dropdown-link-hover-color: #fff;
				            --bs-dropdown-link-active-bg: #3e3232;
				            --bs-dropdown-link-active-color: #fff;
				            ">Tentang Kami</a></li>
				            <li><a class="dropdown-item" onclick="window.location.href='/sepatu/logout.php'" style="
				            --bs-dropdown-link-hover-color: var(--bs-danger);
				            --bs-dropdown-link-active-bg: #d9d9d9;
				            --bs-dropdown-link-active-color: var(--bs-danger);
				            ">Keluar</a></li>
						</ul>
					</div>
				</div>
			</nav>
<?php
	}

	// Footer
	function footer(){ 
		global $username;
		global $role;
		?>
	<div class="container-fluid" style="background-color: #3e3232">
		<footer class="d-flex flex-wrap justify-content-between align-items-center py-3">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="row align-items-center py-2">
							<div class="col-auto"><img src="/sepatu/style/logo/logo.jpeg" style="width: 2.6em" class="rounded"></div>
							<div class="col-4">
								<a onclick="window.location.href='<?php if($username) { if($role == 'Customer') { linkz($username); }else{ linkowner($username); } }else{ linkz($username); }?>'" class="mb-md-0 text-decoration-none text-white">
									QioShoes
								</a>
							</div>
						</div>
						<div class="row align-items-center py-2">
							<!-- <div class="col-auto"><i class="bi bi-geo-alt text-white" style="font-size: 27.5px"></i></div> -->
							<div class="col-auto"><img src="/sepatu/style/img/geo-alt.png" style="width: 2.6em" class="rounded"></div>
							<div class="col-4">
								<a onclick="window.location.href='./'" class="mb-md-0 text-decoration-none text-white">
									Jalan Kemana, Neng
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="row align-items-center py-2 justify-content-end">
							<!-- <div class="col-auto"><i class="bi bi-whatsapp text-white" style="font-size: 27.5px"></i></div> -->
							<div class="col-auto"><img src="/sepatu/style/img/whatsapp.png" style="width: 2.6em" class="rounded"></div>
							<div class="col-4">
								<a href="https://wa.me/6288808580061" class="mb-md-0 text-decoration-none text-white" target='_blank'>
									+62 888-0858-0061
								</a>
							</div>
						</div>
						<div class="row align-items-center py-2 justify-content-end">
							<!-- <div class="col-auto"><i class="bi bi-instagram text-white" style="font-size: 27.5px"></i></div> -->
							<div class="col-auto"><img src="/sepatu/style/img/instagram.png" style="width: 2.6em" class="rounded"></div>
							<div class="col-4">
								<a href="https://instagram.com/dzq.ar" class="mb-md-0 text-decoration-none text-white" target='_blank'>
									@qio_shoes
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row justify-content-center text-white">
					©Copyright Since 2024
				</div>
			</div>
		</footer>
	</div>
<?php
	}
}
$lib = new lib();
?>

<?php 
function rupiah($angka){
	$rupiah = number_format($angka,'0','','.');
	return "Rp. ".$rupiah;
}
 
?>

<?php
// Fungsi untuk memeriksa izin akses sesuai role Customer
function customer() {
    $role = $_SESSION['role'];
    if($role === 'Customer') {
            return true; // Pengguna Customer memiliki izin akses
        }
    return false; // Pengguna bukan Customer atau belum login
}

// Fungsi untuk memeriksa izin akses sesuai role owner
function owner() {
    $role = $_SESSION['role'];
    if($role === 'Owner') {
            return true; // Pengguna owner memiliki izin akses
        }
    return false; // Pengguna bukan owner atau belum login
}
?>