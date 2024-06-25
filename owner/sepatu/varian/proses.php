<?php
session_start();

include '../../../koneksi.php';

$idDataSepatu = $_POST['id_data_sepatu'];
$idSepatu = $_POST['id_sepatu'];
$namaSepatu = $_POST['nama_sepatu'];
$stok = $_POST['stok'];
$ukuranSepatu = $_POST['ukuran'];
$warna = ucfirst($_POST['warna']);
$harga = str_replace(".", "", $_POST['harga']);
$deskripsi = $_POST['deskripsi'];


if (isset($_POST['btn']) || isset($_GET['btn'])) {
	if ($_POST['btn'] === 'TambahVarianSepatu') {
		// Mengecek, apakah nama_sepatu ini sudah pernah ada sebelumnya di database
		$tampilDataSepatu = $s->tampilDataSepatu("COUNT(nama_sepatu) AS ns,data_sepatu.id_data_sepatu AS ids","WHERE nama_sepatu LIKE '%$namaSepatu%' AND warna LIKE '%$warna%' AND ukuran='$ukuranSepatu' AND data_sepatu.id_sepatu='$idSepatu'");
		foreach ($tampilDataSepatu as $key) {
			$countNS = $key['ns'];
			$ids = $key['ids'];
		}
		// var_dump($countNS);
		if ($countNS <= 0) {
			// Jika belum ada, maka akan menambahkannya sebagai data baru

			// Cek Varian Warna
			$dataWarna = mysqli_query($db->koneksi,"SELECT warna,gambar_sepatu FROM data_sepatu WHERE id_sepatu='$idSepatu' AND warna LIKE '%$warna%'");
			$cekWarna = mysqli_num_rows($dataWarna);
			var_dump($cekWarna);
			if ($cekWarna <= 0) {
				// Jika belum ada warna baru di database
				if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
				// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
					$rand = rand();
					$ekstensi = array('png','jpg','jpeg');
					$filename = $_FILES['foto']['name'];
					$ukuran = $_FILES['foto']['size'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					// Mengecek Ekstensi
					if (!in_array($ext,$ekstensi)) {
						header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalEkstensi");
					}else{
						// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
						if ($ukuran < 2044070) {
							$gambarSepatu = $rand.'_'.$filename;

							// Menambah file ke dalam folder 'gambar'
							move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

							$s->tambahDataSepatu($idSepatu,$stok,$ukuranSepatu,$warna,$harga,$gambarSepatu);

							header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahDataSepatu");


						}else{
							// Jika ukurannya lebih besar dari sistem
							header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalUkuran");
						}
					}
				}else{
					header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=errorIssetdanEmptyData");	
				}
			}else{
				while ($d = mysqli_fetch_array($dataWarna)) {
					$gambarSepatu = $d['gambar_sepatu'];
					// var_dump($d);
				}
				$s->tambahDataSepatu($idSepatu,$stok,$ukuranSepatu,$warna,$harga,$gambarSepatu);
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahDataSepatuWithWarnaYangSudahAda");
			}
		}else{
			// Jika sudah ada, maka hanya akan menambahkan stok
			// var_dump($ids,$stok);
			$s->tambahStokDataSepatu($ids,$stok);
			header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahStok");
		}
	}elseif ($_POST['btn'] === 'EditVarianSepatu') {
		// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
		if (isset($_FILES['foto']['name'])) {
			if (!empty($_FILES['foto']['name'])) {
				$rand = rand();
				$ekstensi = array('png','jpg','jpeg');
				$filename = $_FILES['foto']['name'];
				$ukuran = $_FILES['foto']['size'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

			// Mengecek Ekstensi
				if (!in_array($ext,$ekstensi)) {
					header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalEkstensi");
				}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
					if ($ukuran < 2044070) {
						$gambarSepatu = $rand.'_'.$filename;

					// Cek gambar yang sudah ada
						$cek = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM data_sepatu WHERE id_data_sepatu='$idDataSepatu'");
						$c = mysqli_fetch_assoc($cek);

						unlink('gambar/'.$c['gambar_sepatu']);

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

						$s->editDataSepatu($idDataSepatu,$stok,$ukuranSepatu,$warna,$harga,", gambar_sepatu='$gambarSepatu'");

						header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilEditDataSepatu");

					}else{
					// Jika ukurannya lebih besar dari sistem
						header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalUkuran");
					}
				}
			}else{
				$s->editDataSepatu($idDataSepatu,$stok,$ukuranSepatu,$warna,$harga,$deskripsi,'');
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilEditDataSepatu");
			}
		}else{
			header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=errorIssetdanEmptyData");	
		}
	}elseif ($_GET['btn'] === 'HapusVarianSepatu'){
		$s->hapusDataSepatu($_GET['id_data_sepatu']);
		$namaSepatu = $_GET['nama_sepatu'];
		header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilHapusDataSepatu");
	}
}

?>