<?php 
session_start();

include '../koneksi.php';

$id = $_SESSION['id_user'];

$idPesan = (isset($_POST['id_pesan'])) ? $_POST['id_pesan'] : $_GET['id_pesan'];

$idSepatu = (isset($_POST['id_sepatu'])) ? $_POST['id_sepatu'] : $_GET['id_sepatu'];
$ukuran = $_POST['ukuran'];
$warna = $_POST['warna'];
$jumlahBeli = $_POST['jumlahBeli'];
// $harga = $_POST['harga'];

$alasan = mysqli_real_escape_string($db->koneksi,$_POST['alasan']);
$ulasan = mysqli_real_escape_string($db->koneksi,$_POST['ulasan']);
$rating = $_POST['rating'];


$rand = rand(0000,9999);
$kode = "PSK-$id-$rand";

$dataHarga = mysqli_query($db->koneksi,"SELECT harga FROM data_sepatu WHERE id_sepatu='$idSepatu' AND warna='$warna' AND ukuran='$ukuran'");
while ($h = mysqli_fetch_array($dataHarga)) {
	$harga = $h['harga'];
}
// var_dump($idSepatu,$warna,$ukuran);
$total = $harga * $jumlahBeli;

$btn = (isset($_POST['btn'])) ? $_POST['btn'] : $_GET['btn'];

if ($btn === 'beliSepatu') {
	$s->beliSepatu($idSepatu,$kode,$ukuran,$warna,$jumlahBeli,$id,$total);	
}elseif ($btn === 'batalPesanan') {
	$p->batalPesanan($idPesan,$alasan);
	header('location:./pesanan.php?pesan=berhasilBatalPesanan');
}elseif ($btn === 'selesaikanPesanan') {
	$p->selesaikanPesanan($idPesan);
	header('location:./pesanan.php?pesan=berhasilSelesaikanPesanan');
}elseif ($btn === 'ratePesanan') {
	$p->ratePesanan($id,$idSepatu,$ulasan,$rating,$idPesan);
	header('location:./pesanan.php?pesan=berhasilRatingPesanan');
}elseif ($btn === 'lihatUlasan') {
	$_SESSION['idSepatu'] = $idSepatu;
	if (isset($_POST['id_user'])) {
		print_r($idUser = md5($_POST['id_user']));
		header("location:./detail_rating.php#$idUser");
	}else{
		header('location:./detail_rating.php');
	}
}elseif ($btn === 'backDetail') {
	$_SESSION['idSepatu'] = $idSepatu;
	header('location:./detail.php');
}else{
	header('location:./?pesan=noBtnDetected');
}

?>