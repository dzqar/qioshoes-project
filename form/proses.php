<?php
session_start();

include '../koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['btn'])) {
	$btn = $_POST['btn'];
	if ($btn === 'Daftar') {
		// Kondisi jika username sudah/pernah terdaftar
		$check = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username='$username'");
		if (mysqli_num_rows($check) > 0) {
		// Mengalihkan ke halaman daftar.php dan memberi pesan jika ada username yang terdaftar sebelumnya
			header('location:/sepatu/?pesan=usernameAlready');
		}else{
			// Menginput data ke database tbl_user jika tidak ada data yang sama
			$gc->daftar($username, $password);
			// Mengalihkan halaman ke index.php
			// header('location:/sepatu/');

			// Menangkap data nilai dari form inputan terdapat di input.php
			$username = mysqli_real_escape_string($db->koneksi, $_POST['username']);
			$password = mysqli_real_escape_string($db->koneksi, $_POST['password']);
		// Login untuk memeriksa keberadaan user dalam tabel user
			$login = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username = '$username' AND password = '$password'");
			$cek = mysqli_num_rows($login);

		// cek apakah username dan password di temukan pada database
			if($cek > 0){

				$data = mysqli_fetch_assoc($login);

  			//Mengecek apakah username dan passwordnya SAMA seperti yang ada di database
				if ($username === $data['username'] && $password === $data['password']) {
				// cek jika user login sebagai Owner
					if ($_POST['rm']) {
						setcookie('status','true',time() + (3600),'/');
						setcookie('role',$data['role'],time() + (3600),'/');
						if($data['role']=="Owner"){

					// buat session login dan username
							$_SESSION['id_user'] = $data['id_user'];
							$_SESSION['username'] = $data['username'];
							$_SESSION['role'] = "Owner";
							$_SESSION['is_logged_in'] = true;	
					// alihkan ke halaman dashboard Owner
							header("location:../owner/");

				// cek jika user login sebagai Customer
						}else if($data['role']=="Customer"){
					// buat session login dan username
							$_SESSION['id_user'] = $data['id_user'];
							$_SESSION['username'] = $data['username'];
							$_SESSION['role'] = "Customer";
							$_SESSION['is_logged_in'] = true;
							(isset($_POST['id_sepatu'])) ? $_SESSION['idSepatu'] = $_POST['id_sepatu'] : NULL;
					// alihkan ke halaman dashboard pegawai
							header("location:../customer/");
						}else{
					// alihkan ke halaman login kembali
							header("location:/sepatu/?pesan=gagalNoRole");
						}
					}else{
						if($data['role']=="Owner"){

					// buat session login dan username
							$_SESSION['id_user'] = $data['id_user'];
							$_SESSION['username'] = $data['username'];
							$_SESSION['role'] = "Owner";
							$_SESSION['is_logged_in'] = true;	
					// alihkan ke halaman dashboard Owner
							header("location:../owner/");

				// cek jika user login sebagai Customer
						}else if($data['role']=="Customer"){
					// buat session login dan username
							$_SESSION['id_user'] = $data['id_user'];
							$_SESSION['username'] = $data['username'];
							$_SESSION['role'] = "Customer";
							$_SESSION['is_logged_in'] = true;
							(isset($_POST['id_sepatu'])) ? $_SESSION['idSepatu'] = $_POST['id_sepatu'] : NULL;
					// alihkan ke halaman dashboard pegawai
							header("location:../customer/");
						}else{
					// alihkan ke halaman login kembali
							header("location:/sepatu/?pesan=gagalNoRole");
						}
					}
				}else{
  				// Dialihkan kembali ke halaman login jika usernamenya tidak sesuai yang ada di database
					header("location:/sepatu/?pesan=gagalSalahUserorPass");
				}
			}else{
				header("location:/sepatu/?pesan=dataNotFound");

				// Buat mencegah login berulang
				$_SESSION['auth'] = $_SESSION['auth'];
				$_SESSION['pass'] = NULL;
				if(isset($_SESSION['auth'])){
					$_SESSION['auth']++;
				}else{
					$_SESSION['auth'] = 1;
				}
			}
		}
	}elseif ($btn === 'Login') {
		// Menangkap data nilai dari form inputan terdapat di input.php
		$username = mysqli_real_escape_string($db->koneksi, $_POST['username']);
		$password = mysqli_real_escape_string($db->koneksi, $_POST['password']);
		// Login untuk memeriksa keberadaan user dalam tabel user
		$login = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username = '$username' AND password = '$password'");
		$cek = mysqli_num_rows($login);

		// cek apakah username dan password di temukan pada database
		if($cek > 0){

			$data = mysqli_fetch_assoc($login);

  			//Mengecek apakah username dan passwordnya SAMA seperti yang ada di database
			if ($username === $data['username'] && $password === $data['password']) {
				// cek jika user login sebagai Owner
				if ($_POST['rm']) {
					setcookie('status','true',time() + (3600),'/');
					setcookie('role',$data['role'],time() + (3600),'/');
					if($data['role']=="Owner"){

					// buat session login dan username
						$_SESSION['id_user'] = $data['id_user'];
						$_SESSION['username'] = $data['username'];
						$_SESSION['role'] = "Owner";
						$_SESSION['is_logged_in'] = true;
					// alihkan ke halaman dashboard Owner
						header("location:../owner/");

				// cek jika user login sebagai Customer
					}else if($data['role']=="Customer"){
					// buat session login dan username
						$_SESSION['id_user'] = $data['id_user'];
						$_SESSION['username'] = $data['username'];
						$_SESSION['role'] = "Customer";
						$_SESSION['is_logged_in'] = true;
						(isset($_POST['id_sepatu'])) ? $_SESSION['idSepatu'] = $_POST['id_sepatu'] : NULL;
					// alihkan ke halaman dashboard pegawai
						header("location:../customer/");
					}else{
					// alihkan ke halaman login kembali
						header("location:/sepatu/?pesan=gagalNoRole");
					}
				}else{
					if($data['role']=="Owner"){

					// buat session login dan username
						$_SESSION['id_user'] = $data['id_user'];
						$_SESSION['username'] = $data['username'];
						$_SESSION['role'] = "Owner";
						$_SESSION['is_logged_in'] = true;
					// alihkan ke halaman dashboard Owner
						header("location:../owner/");

				// cek jika user login sebagai Customer
					}else if($data['role']=="Customer"){
					// buat session login dan username
						$_SESSION['id_user'] = $data['id_user'];
						$_SESSION['username'] = $data['username'];
						$_SESSION['role'] = "Customer";
						$_SESSION['is_logged_in'] = true;
						(isset($_POST['id_sepatu'])) ? $_SESSION['idSepatu'] = $_POST['id_sepatu'] : NULL;
					// alihkan ke halaman dashboard pegawai
						header("location:../customer/");
					}else{
					// alihkan ke halaman login kembali
						header("location:/sepatu/?pesan=gagalNoRole");
					}
				}
			}else{
  				// Dialihkan kembali ke halaman login jika usernamenya tidak sesuai yang ada di database
				header("location:/sepatu/?pesan=gagal");
			}
		}else{
			header("location:/sepatu/?pesan=gagal");

				// Buat mencegah login berulang
			$_SESSION['auth'] = $_SESSION['auth'];
			$_SESSION['pass'] = NULL;
			if(isset($_SESSION['auth'])){
				$_SESSION['auth']++;
			}else{
				$_SESSION['auth'] = 1;
			}
		}
	}else{
		header('location:/sepatu/?pesan=noBtnDetected');
	}
}else{
	header('location:/sepatu/?pesan=noIssetDetected');
}

?>