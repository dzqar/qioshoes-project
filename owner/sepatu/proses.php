<?php
session_start();

include '../../koneksi.php';

$btn = (isset($_POST['btn'])) ? $_POST['btn'] : $_GET['btn'];

$jum = $_POST['jum'];

$idDataSepatu = (isset($_POST['id_data_sepatu'])) ? $_POST['id_data_sepatu'] : $_GET['id_data_sepatu'];
$idSepatu = (isset($_POST['id_sepatu'])) ? $_POST['id_sepatu'] : $_GET['id_sepatu'];
$merkSepatu = $_POST['merk_sepatu'];
$ukuranSepatu = $_POST['ukuran'];
$stok = $_POST['stok'];
$harga = str_replace(".", "", $_POST['harga']);

for ($i=0; $i < $jum; $i++) { 
	$namaSepatu[$i] = mysqli_real_escape_string($db->koneksi,(isset($_POST['nama_sepatu'])) ? $_POST['nama_sepatu'][$i] : $_GET['nama_sepatu'][$i]);
	$deskripsi[$i] = mysqli_real_escape_string($db->koneksi,$_POST['deskripsi'][$i]);
	$warna[$i] = mysqli_real_escape_string($db->koneksi,$_POST['warna'][$i]);
}


// echo $btn;

if ($btn === 'TambahSepatu') {
	for ($i=0; $i < $jum; $i++) { 
		$data = mysqli_query($db->koneksi,"SELECT * FROM sepatu WHERE nama_sepatu='$namaSepatu[$i]'");
		if (mysqli_num_rows($data) > 0) {
			header('location:./?pesan=sepatuSudahAda');
			exit;
		}
	}

	// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size']; 
		foreach ($filename as $fn) {
			$ext = pathinfo($fn, PATHINFO_EXTENSION);
		}

		// Mengecek Ekstensi
		if (!in_array($ext,$ekstensi)) {
			header('location:/sepatu/owner/sepatu/?pesan=gagalEkstensi');
		}else{
			// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
			foreach ($ukuran as $ukur) {
				$pukur = $ukur;
			}
			if ($pukur < 2044070) {
				// $gambarSepatu = $namaSepatu.'default_';

				for ($i=0; $i < $jum; $i++) { 
					$fixNamaSepatu = str_replace(" ","",strtolower($namaSepatu[$i]));
					$gambarSepatu = $fixNamaSepatu.'-default.'.$ext;
					var_dump($gambarSepatu);
					// Menambah file ke dalam folder 'gambar'
					$tmp = $_FILES['foto']['tmp_name'];
					move_uploaded_file($tmp[$i],'gambar/'.$gambarSepatu);

					$s->tambahSepatu($namaSepatu[$i],$gambarSepatu,$deskripsi[$i]);
				}

				header('location:/sepatu/owner/sepatu/?pesan=berhasilTambahSepatu');

			}else{
					// Jika ukurannya lebih besar dari sistem
				header('location:/sepatu/owner/sepatu/?pesan=gagalUkuran');
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
		$namaSepatu = mysqli_real_escape_string($db->koneksi,$_POST['nama_sepatu']);

		if (!empty($_FILES['foto']['name'])) {
				// Mengecek Ekstensi
			if (!in_array($ext,$ekstensi)) {
				header('location:/sepatu/owner/sepatu/?pesan=gagalEkstensi');
			}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
				if ($ukuran < 2044070) {
					$fixNamaSepatu = str_replace(" ","",strtolower($namaSepatu));
					$gambarSepatu = $fixNamaSepatu.'-default.'.$ext;

					// Mengecek data di column 'foto' berdasakan id_user
					$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
					$cs = mysqli_fetch_assoc($cek);

					unlink('gambar/'.$cs['gambar_sepatu']);

					// Menambah file ke dalam folder 'gambar'
					move_uploaded_file($_FILES['foto']['tmp_name'],'gambar/'.$gambarSepatu);

					$deskripsi = mysqli_real_escape_string($db->koneksi,$_POST['deskripsi']);
					$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi', gambar_sepatu='$gambarSepatu'");

					header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');

				}else{
					// Jika ukurannya lebih besar dari sistem
					header('location:/sepatu/owner/sepatu/add.php?pesan=gagalUkuran');
				}
			}
		}else{
			$deskripsi = mysqli_real_escape_string($db->koneksi,$_POST['deskripsi']);
			$s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
			header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');
		}
	}else{
		// $s->editSepatu($idSepatu,$namaSepatu,", deskripsi='$deskripsi'");
		// header('location:/sepatu/owner/sepatu/?pesan=berhasilEditSepatu');
		header('location:/sepatu/owner/sepatu/?pesan=errorIssetdanEmpty');	
	}
}elseif ($btn === 'HapusSepatu'){
	/*$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu'");
	$cs = mysqli_fetch_assoc($cek);*/
	$cek = mysqli_query($s->koneksi,"SELECT gambar_sepatu AS gsds FROM data_sepatu WHERE id_sepatu='$idSepatu' GROUP BY warna");
	while($cs = mysqli_fetch_array($cek)) {
		unlink('gambar/'.$cs['gsds']);
	}

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
	for ($i=0; $i < $jum; $i++) { 
		// Mengecek, apakah nama_sepatu ini sudah pernah ada sebelumnya di database
		$tampilDataSepatu = $s->tampilDataSepatu("COUNT(nama_sepatu) AS ns,data_sepatu.id_data_sepatu AS ids","WHERE nama_sepatu LIKE '%$namaSepatu[$i]%' AND warna LIKE '%$warna[$i]%' AND ukuran='$ukuranSepatu[$i]' AND data_sepatu.id_sepatu='$idSepatu[$i]'");
		foreach ($tampilDataSepatu as $key) {
			$countNS = $key['ns'];
			$ids = $key['ids'];
		}
		// var_dump($ids);
		// exit;
		if ($countNS <= 0) {
			// Jika belum ada, maka akan menambahkannya sebagai data baru

			// Cek Varian Warna
			$dataWarna = mysqli_query($db->koneksi,"SELECT COUNT(warna) AS warna, MAX(gambar_sepatu) AS gambar_sepatu, MAX(id_data_sepatu) AS maxIds FROM data_sepatu WHERE id_sepatu='$idSepatu[$i]' AND warna LIKE '%$warna[$i]%'");
			// $cekWarna = mysqli_num_rows($dataWarna);
			$cekWarna = mysqli_fetch_assoc($dataWarna);
			// var_dump($cekWarna[$i]);
			// exit;
			// var_dump($cekWarna['warna']);
			if ($cekWarna['warna'] <= 0) {
			// Jika belum ada warna baru di database
				if (isset($_FILES['foto']['name'])) {
				// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
					$rand = rand(100,999);
					$ekstensi = array('png','jpg','jpeg');
					$filename = $_FILES['foto']['name'];
					$ukuran = $_FILES['foto']['size'];
					foreach ($filename as $fn) {
						$ext = pathinfo($fn, PATHINFO_EXTENSION);
					}

					if (!empty($filename[$i])) {
					// Mengecek Ekstensi
						if (!in_array($ext,$ekstensi)) {
							header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=gagalEkstensi");
						}else{
						// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
							foreach ($ukuran as $ukur) {
								$pukur = $ukur;
							}
							if ($pukur < 2044070) {
								// $fixNamaSepatu = str_replace(" ","",strtolower($_POST['nama_sepatu']));
								$fixNamaSepatu = str_replace(" ","",strtolower($namaSepatu[$i]));
								$gambarSepatu = $rand.$idSepatu[$i].'_'.$fixNamaSepatu."-$warna[$i].".$ext;
								$tmp = $_FILES['foto']['tmp_name'];

							// Menambah file ke dalam folder 'gambar'
								move_uploaded_file($tmp[$i],'gambar/'.$gambarSepatu);

								$s->tambahDataSepatu($idSepatu[$i],$stok[$i],$ukuranSepatu[$i],$warna[$i],$harga[$i],$gambarSepatu);
								header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=berhasilTambahDataSepatu");

							}else{
							// Jika ukurannya lebih besar dari sistem
								header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=gagalUkuran");
							}
						}
					}else{
						$getGambar = mysqli_query($db->koneksi,"SELECT gambar_sepatu FROM sepatu WHERE id_sepatu='$idSepatu[$i]'");
						while ($d = mysqli_fetch_array($getGambar)) {
							$gambarSepatu = $d['gambar_sepatu'];
						}
						$s->tambahDataSepatu($idSepatu[$i],$stok[$i],$ukuranSepatu[$i],$warna[$i],$harga[$i],$gambarSepatu);
						header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=berhasilTambahDataSepatuNoGambar");
					}
				}else{
					header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=errorIssetdanEmptyData");
				}
			}else{
				// Jika variannya itu udh pernah ada, maka akan mengambil gambar dari data sebelumnya berdasarkan id_sepatu
				/*while ($d = mysqli_fetch_array($dataWarna)) {
					$gambarSepatu = $d['gambar_sepatu'];
				}
				print_r($d);*/
				$gambarSepatu = $cekWarna['gambar_sepatu'];
				$s->tambahDataSepatu($idSepatu[$i],$stok[$i],$ukuranSepatu[$i],$warna[$i],$harga[$i],$gambarSepatu);
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=berhasilTambahDataSepatuWithWarnaYangSudahAda");
			}
		}else{
			// Jika sudah ada, maka hanya akan menambahkan stok
			// var_dump($ids,$stok);
			$s->tambahStokDataSepatu($ids,$stok[$i]);
			header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu[$i]&pesan=berhasilTambahStok");
		}
	}
}elseif ($btn === 'EditVarianSepatu') {
	// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
	if (isset($_FILES['foto']['name'])) {
		$rand = rand();
		$ekstensi = array('png','jpg','jpeg');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$namaSepatu = mysqli_real_escape_string($db->koneksi,$_POST['nama_sepatu']);
		$warna = mysqli_real_escape_string($db->koneksi,$_POST['warna']);

		if (!empty($_FILES['foto']['name'])) {
			// Mengecek Ekstensi
			if (!in_array($ext,$ekstensi)) {
				header("location:/sepatu/owner/sepatu/varian?nama_sepatu=$namaSepatu&pesan=gagalEkstensi");
			}else{
				// Mengecek Ukuran Jika ukurannya lebih kecil dari 2mb
				if ($ukuran < 2044070) {
					$fixNamaSepatu = str_replace(" ","",strtolower($namaSepatu));
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