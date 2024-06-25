<?php
session_start();

include '../../koneksi.php';

$idPesan = (isset($_GET['id_pesan'])) ? $_GET['id_pesan'] : $_POST['id_pesan'];

$btn = (isset($_GET['btn'])) ? $_GET['btn'] : $_POST['btn'];

if ($btn === 'Terima') {
	$p->terimaPesanan($idPesan);
	header('location:../pesanan?pesan=berhasilTerimaPesanan');
}elseif ($btn === 'Tolak') {
	$alasan = mysqli_real_escape_string($db->koneksi,$_POST['alasan']);
	$p->tolakPesanan($idPesan,$alasan);
	header('location:../pesanan?pesan=berhasilTolakPesanan');
}elseif ($btn === 'TolakKadaluarsa') {
	$p->kadaluarsaPesanan($idPesan);
	header('location:../pesanan?pesan=berhasilTolakKadaluarsaPesanan');
}elseif ($btn === 'siapDiambil') {
	$p->siapDiambilPesanan($idPesan);
	header('location:../pesanan?pesan=berhasilSiapAmbilPesanan');
}elseif ($btn === 'selesaikanPesanan') {
	$p->selesaikanPesanan($idPesan);
	header('location:../pesanan?pesan=berhasilSelesaikanPesanan');
}else{
	header('location:../pesanan?pesan=noBtn');
}


?>