<?php
session_start();

include '../../koneksi.php';

$btn = (isset($_POST['btn'])) ? $_POST['btn'] : $_GET['btn'];

$idSepatu = $_POST['id_sepatu'];
$merkSepatu = $_POST['merk_sepatu'];
$namaSepatu = $_POST['nama_sepatu'];
$deskripsi = $_POST['deskripsi'];
$ukuran = $_POST['ukuran'];
$warna = $_POST['warna'];
$stok = $_POST['stok'];
$harga = $_POST['harga'];

if ($btn === 'TambahSepatu') {
		// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

			// Mengecek Ekstensi
		if (!in_array($ext,$ekstensi)) {
			header('location:../sepatu/?pesan=gagalEkstensi');
		}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
			if ($ukuran < 2044070) {
				$gambarSepatu = $rand.'_'.$filename;

					// Menambah file ke dalam folder 'gambar'
				move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

				$s->tambahSepatu($namaSepatu,$gambarSepatu,$deskripsi);

				header('location:../sepatu/?pesan=berhasilTambahSepatu');

			}else{
					// Jika ukurannya lebih besar dari sistem
				header('location:../sepatu/add.php?pesan=gagalUkuran');
			}
		}
	}else{
		header('location:../sepatu/?pesan=errorIssetdanEmpty');	
	}
}elseif ($btn === 'EditSepatu'){
		// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if (!empty($_FILES['foto']['name'])) {
				// Mengecek Ekstensi
			if (!in_array($ext,$ekstensi)) {
				header('location:../sepatu/?pesan=gagalEkstensi');
			}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
				if ($ukuran < 2044070) {
					$gambarSepatu = $rand.'_'.$filename;

					// Mengecek data di column 'foto' berdasakan id_user
					$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
					$cs = mysqli_fetch_assoc($cek);

					unlink('gambar/'.$cs['gambar_sepatu']);

					// Menambah file ke dalam folder 'gambar'
					move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

					$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi', gambar_sepatu='$gambarSepatu'");

					header('location:../sepatu/?pesan=berhasilEditSepatu');

				}else{
					// Jika ukurannya lebih besar dari sistem
					header('location:../sepatu/add.php?pesan=gagalUkuran');
				}
			}
		}else{
			$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
			header('location:../sepatu/?pesan=berhasilEditSepatu');
			// header('location:../sepatu/?pesan=errorIssetdanEmpty');	
		}
	}else{
		$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
		header('location:../sepatu/?pesan=berhasilEditSepatu');
	}
}elseif ($btn === 'HapusSepatu'){
	$s->hapusSepatu($_GET['id_sepatu']);
	header('location:../sepatu/?pesan=berhasilHapusSepatu');
}elseif ($btn === 'hapusGambar') {
	$cekGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
	$cg = mysqli_fetch_assoc($cekGambar)['gambar_sepatu'];
	echo $cg;
	if ($cg) {
		unlink('gambar/'.$cg);
	}

	$s->hapusGambarSepatu($idSepatu);
	header('location:../sepatu/?pesan=berhasilHapusGambarSepatu');
}else{
	header('location:../sepatu/?pesan=noBtnDetected');
}

?>
<!-- SEBELUM DITAMBAHIN MULTIPLE RECORD DATA -->
<?php
session_start();

include '../../koneksi.php';

$btn = (isset($_POST['btn'])) ? $_POST['btn'] : $_GET['btn'];

$idDataSepatu = (isset($_POST['id_data_sepatu'])) ? $_POST['id_data_sepatu'] : $_GET['id_data_sepatu'];
$idSepatu = (isset($_POST['id_sepatu'])) ? $_POST['id_sepatu'] : $_GET['id_sepatu'];
$merkSepatu = $_POST['merk_sepatu'];
$namaSepatu = (isset($_POST['nama_sepatu'])) ? $_POST['nama_sepatu'] : $_GET['nama_sepatu'];
$deskripsi = $_POST['deskripsi'];
$ukuranSepatu = $_POST['ukuran'];
$stok = $_POST['stok'];
$warna = ucfirst($_POST['warna']);
$harga = str_replace(".", "", $_POST['harga']);

// echo $btn;

if ($btn === 'TambahSepatu') {
	// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

			// Mengecek Ekstensi
		if (!in_array($ext,$ekstensi)) {
			header('location:/sepatu/owner/sepatu/?pesan=gagalEkstensi');
		}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
			if ($ukuran < 2044070) {
				// $gambarSepatu = $namaSepatu.'default_';
				$fixNamaSepatu = str_replace(" ","",strtolower($_POST['nama_sepatu']));
				$gambarSepatu = $fixNamaSepatu.'-default.'.$ext;
				
					// Menambah file ke dalam folder 'gambar'
				move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

				$s->tambahSepatu($namaSepatu,$gambarSepatu,$deskripsi);

				header('location:/sepatu/owner/sepatu/?pesan=berhasilTambahSepatu');

			}else{
					// Jika ukurannya lebih besar dari sistem
				header('location:/sepatu/owner/sepatu/add.php?pesan=gagalUkuran');
			}
		}
	}else{
		header('location:/sepatu/owner/sepatu/?pesan=errorIssetdanEmpty');	
	}
}elseif ($btn === 'EditSepatu'){
	// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if (!empty($_FILES['foto']['name'])) {
				// Mengecek Ekstensi
			if (!in_array($ext,$ekstensi)) {
				header('location:/sepatu/owner/sepatu/?pesan=gagalEkstensi');
			}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
				if ($ukuran < 2044070) {
					$fixNamaSepatu = str_replace(" ","",strtolower($_POST['nama_sepatu']));
					$gambarSepatu = $fixNamaSepatu.'-default.'.$ext;

					// Mengecek data di column 'foto' berdasakan id_user
					$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
					$cs = mysqli_fetch_assoc($cek);

					unlink('gambar/'.$cs['gambar_sepatu']);

					// Menambah file ke dalam folder 'gambar'
					move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

					$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi', gambar_sepatu='$gambarSepatu'");

					header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');

				}else{
					// Jika ukurannya lebih besar dari sistem
					header('location:/sepatu/owner/sepatu/add.php?pesan=gagalUkuran');
				}
			}
		}else{
			$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
			header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');
		}
	}else{
		// $s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
		// header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');
		header('location:/sepatu/owner/sepatu/?pesan=errorIssetdanEmpty');	
	}
}elseif ($btn === 'HapusSepatu'){
	$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
	$cs = mysqli_fetch_assoc($cek);
	unlink('gambar/'.$cs['gambar_sepatu']);
	
	$s->hapusSepatu($idSepatu);
	header('location:/sepatu/owner/sepatu/?pesan=berhasilHapusSepatu');
}elseif ($btn === 'hapusGambar') {
	$cekGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
	$cg = mysqli_fetch_assoc($cekGambar)['gambar_sepatu'];
	echo $cg;
	if ($cg) {
		unlink('gambar/'.$cg);
	}

	$s->hapusGambarSepatu($idSepatu);
	header('location:/sepatu/owner/sepatu/?pesan=berhasilHapusGambarSepatu');
}elseif($btn === 'TambahVarianSepatu') {
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
		$dataWarna = mysqli_query($db->koneksi,"SELECT COUNT(warna) AS warna, MAX(gambar_sepatu) AS gambar_sepatu, MAX(id_data_sepatu) AS maxIds FROM data_sepatu WHERE id_sepatu='$idSepatu' AND warna LIKE '%$warna%'");
		// $cekWarna = mysqli_num_rows($dataWarna);
		$cekWarna = mysqli_fetch_assoc($dataWarna);
		// var_dump($cekWarna['warna']);
		if ($cekWarna['warna'] <= 0) {
			// Jika belum ada warna baru di database
			if (isset($_FILES['foto']['name'])) {
				// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
				$rand = rand(100,999);
				$ekstensi = array('png','jpg','jpeg');
				$filename = $_FILES['foto']['name'];
				$ukuran = $_FILES['foto']['size'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if (!empty($_FILES['foto']['name'])) {
					// Mengecek Ekstensi
					if (!in_array($ext,$ekstensi)) {
						header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalEkstensi");
					}else{
						// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
						if ($ukuran < 2044070) {
							$fixNamaSepatu = str_replace(" ","",strtolower($_POST['nama_sepatu']));
							$gambarSepatu = $rand.$idSepatu.'_'.$fixNamaSepatu."-$warna.".$ext;

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
					$getGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
					while ($d = mysqli_fetch_array($getGambar)) {
						$gambarSepatu = $d['gambar_sepatu'];
					}
					$s->tambahDataSepatu($idSepatu,$stok,$ukuranSepatu,$warna,$harga,$gambarSepatu);
					header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahDataSepatuNoGambar");
				}
			}else{
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=errorIssetdanEmptyData");
			}
		}else{
			// Jika variannya itu udh pernah ada, maka akan mengambil gambar dari data sebelumnya berdasarkan id_sepatu
			/*while ($d = mysqli_fetch_array($dataWarna)) {
				$gambarSepatu = $d['gambar_sepatu'];
			}
			print_r($d);*/
			$gambarSepatu = $cekWarna['gambar_sepatu'];
			$s->tambahDataSepatu($idSepatu,$stok,$ukuranSepatu,$warna,$harga,$gambarSepatu);
			header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahDataSepatuWithWarnaYangSudahAda");
		}
	}else{
			// Jika sudah ada, maka hanya akan menambahkan stok
			// var_dump($ids,$stok);
		$s->tambahStokDataSepatu($ids,$stok);
		header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilTambahStok");
	}
}elseif ($btn === 'EditVarianSepatu') {
	// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if (!empty($_FILES['foto']['name'])) {
			// Mengecek Ekstensi
			if (!in_array($ext,$ekstensi)) {
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalEkstensi");
			}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
				if ($ukuran < 2044070) {
					$fixNamaSepatu = str_replace(" ","",strtolower($_POST['nama_sepatu']));
					$gambarSepatu = $rand.$idSepatu.'_'.$fixNamaSepatu."-$warna.".$ext;

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
			$s->editDataSepatu($idDataSepatu,$stok,$ukuranSepatu,$warna,$harga,'');
			header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilEditDataSepatu");
		}
	}else{
		header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=errorIssetdanEmptyData");	
	}
}elseif ($btn === 'HapusVarianSepatu'){
	$s->hapusDataSepatu($idDataSepatu);
	header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=berhasilHapusDataSepatu");
}else{
	header('location:/sepatu/owner/sepatu/?pesan=noBtnDetected');
}

?>