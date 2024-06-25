<?php
// Fungsi untuk memeriksa izin akses sesuai role Customer
function customer() {
    $role = $_SESSION['role'];
    if($role === 'customer') {
            return true; // Pengguna Customer memiliki izin akses
        }
    return false; // Pengguna bukan Customer atau belum login
}

// Fungsi untuk memeriksa izin akses sesuai role owner
function owner() {
    $role = $_SESSION['role'];
    if($role === 'owner') {
            return true; // Pengguna owner memiliki izin akses
        }
    return false; // Pengguna bukan owner atau belum login
}
?>