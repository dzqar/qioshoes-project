// UNTUK SEMUA HALAMAN TIAP LEVEL
<?php 
/*Jika sudah pernah login/tidak bisa back ke halaman sebelumnya jika belum log out*/
if(isset($_GET['pesan'])){
  if($_GET['pesan']=="welcome"){
    ?>
    Swal.fire({
      icon: 'info',
      title: 'Halo!',
      text: 'Selamat datang kembali, <?php echo $username; ?>!',
      confirmButtonText: 'Ok',
      confirmButtonColor: "#3e3232",
      allowOutsideClick: false,
      allowEscapeKey: false,
      footer: '<a href="../logout.php">Log Out?</a>'
    }).then((result) => {
      if (result.isConfirmed) {
  window.location="../<?= $role; ?>/"//Ganti sesuai level/hak akses
}
})

    <?php
    /*Ketika tidak memiliki hak akses tersebut*/
  }elseif ($_GET['pesan']=="noaccess") {
    ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Anda tidak memiliki akses, <?php echo $username; ?>!',
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: "#3e3232",
      confirmButtonText: 'Ok'
    }).then((result) => {
      if (result.isConfirmed) {
window.location='../<?= $role; ?>/' //Ganti sesuai level/hak akses
}
})
    <?php
    // Daftar
  }elseif ($_GET['pesan']=="tryAccess") {
    ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Anda tidak memiliki akses!',
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: "#3e3232",
      confirmButtonText: 'Ok'
    }).then((result) => {
      if (result.isConfirmed) {
window.location='/sepatu/' //Ganti sesuai level/hak akses
}
})
    <?php
    // Daftar
  }elseif($_GET['pesan']=="usernameAlready"){
    // Jika username sudah terdaftar
    ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Username Sudah Terdaftar!',
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: "#3e3232",
      confirmButtonText: 'Baiklah'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "daftar.php"
      }
    })
    <?php
        // Login
      }elseif($_GET['pesan']=="gagal"){
        ?>
  //Jika username dan/atau passwordnya salah
  Swal.fire({
    icon: 'error',
    title: 'Salah!',
    text: 'Username atau password anda salah!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
//Di alihkan ke halaman login.php
window.location = "./"
}
})
  <?php
  // Login
}elseif ($_GET['pesan']=="belum_login") {
  ?>
  //Jika belum pernah melakukan login sebelumnya
  Swal.fire({
    icon: 'question',
    title: 'Belum Login!',
    text: 'Anda belum pernah melakukan login sebelumnya!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Login'
  }).then((result) => {
    if (result.isConfirmed) {
      //Di alihkan ke halaman login.php
      $('#loginModal').modal('show')
    }
  })
  <?php
}elseif ($_GET['pesan'] == 'gagalUkuran'){
  ?>
  //Jika username dan/atau passwordnya salah
  Swal.fire({
    icon: 'error',
    title: 'Ukuran terlalu besar!',
    text: 'Coba menggunakan foto yang ukurannya lebih kecil!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
//Di alihkan ke halaman login.php
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
}elseif ($_GET['pesan'] == 'gagalEkstensi'){
  ?>
  //Jika username dan/atau passwordnya salah
  Swal.fire({
    icon: 'error',
    title: 'Ekstensi tidak sesuai!',
    text: 'Coba ganti format foto yang sesuai!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
//Di alihkan ke halaman login.php
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
}elseif ($_GET['pesan'] == 'gagalHapusFotoDefault'){
  ?>
  //Jika username dan/atau passwordnya salah
  Swal.fire({
    icon: 'error',
    title: 'Gagal Hapus Foto!',
    text: 'Anda tidak bisa menghapus foto bawaan sistem!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
//Di alihkan ke halaman login.php
window.location = "./"
}
})

// AWAL FUNCTION PESAN POP UP CUSTOMER

// PESANAN
<?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilBeliSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menambah ke Daftar Pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./pesanan.php"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilTerimaPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menerima pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilSiapAmbilPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil mengubah status pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilSelesaikanPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menyelesaikan pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilBatalPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil membatalkan Pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./pesanan.php"
}
})
  <?php
}elseif ($_GET['pesan'] == 'stokSepatuHabis') {
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Gagal membuat Pesanan!',
    text: 'Maaf, stok sepatu habis !',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./proses.php?id_sepatu=<?= $_SESSION['idSepatu']?>&btn=backDetail"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'stokSepatuTidakCukup') {
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Gagal membuat Pesanan!',
    text: 'Maaf, stok sepatu tersisa '+<?= $_SESSION['stokSepatu']?>+'!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
      <?php $_SESSION['stokSepatu'] = '' ?>
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./proses.php?id_sepatu=<?= $_SESSION['idSepatu']?>&btn=backDetail"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilRatingPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Terima Kasih Telah Memberikan Ulasan Anda!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./pesanan.php"
}
})
// AKHIR FUNCTION PESAN POP UP CUSTOMER

// AWAL FUNCTION PESAN POP UP KASIR

// DATA BAHAN
// PESANAN
<?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilSelesaiPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menyelesaikan Pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilBatalPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil membatalkan Pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilTerimaPesanan') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menerima Pesanan!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "../<?= $role; ?>/"
}
})
// TRANSAKSI
<?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilTambahSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menambahkan Sepatu Baru!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'sepatuSudahAda') {
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Sepatu sudah pernah di input sebelumnya!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilTambahDataSepatu' || $_GET['pesan'] == 'berhasilTambahDataSepatuWithWarnaYangSudahAda' || $_GET['pesan'] == 'berhasilTambahDataSepatuNoGambar') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menambahkan Varian Sepatu Baru!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilTambahStok') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menambahkan Stok Varian Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilEditDataSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Mengedit Varian Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilEditSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Mengedit Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka halaman akan di refresh
window.location = "./"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilHapusDataSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menghapus Varian Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilHapusSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menghapus Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilHapusGambarSepatu') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil Menghapus Gambar Sepatu!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'errorIssetdanEmpty') {
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Foto kosong! Harus diisi!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'errorIssetdanEmptyData') {
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Foto kosong! Harus diisi!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "./?nama_sepatu=<?= $_GET['nama_sepatu']?>"
}
})
  <?php
// Jika pesan = 'berhasilLB'
}elseif ($_GET['pesan'] == 'berhasilSelesaiTransaksi') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil menyelesaikan Transaksi!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "../<?= $role; ?>/"
}
})
  <?php
}elseif ($_GET['pesan'] == 'berhasilBatalTransaksi') {
  ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil membatalkan Transaksi!',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: "#3e3232",
    confirmButtonText: 'Ok'
  }).then((result) => {
    if (result.isConfirmed) {
// Jika di confirm, maka akan halaman akan di refresh
window.location = "../<?= $role; ?>/"
}
})
// AKHIR FUNCTION PESAN POP UP KASIR
<?php
}
}
?>
// AKHIR FUNCTION PESAN POP UP ADMIN

<?php
if (isset($_SESSION['auth'])) {
  if ($_SESSION['auth'] >= 3) { ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Anda sudah gagal untuk yang ke-3 kalinya!',
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: "#3e3232",
      confirmButtonText: 'Ok'
    })
    <?php
// Kalo mau terus"an di disabled form nya, matiin session_destroy() kecuali kalo lu mau aktifin tiap refresh/bolak-balik halaman
session_destroy();
// exit();
}
}
?>