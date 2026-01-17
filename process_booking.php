<?php
session_start(); include 'koneksi.php';
$user_id = $_SESSION['user_id'];
$nama = $_SESSION['username'];
$mobil_id = $_POST['mobil_id'];
$mulai = $_POST['mulai'];
$selesai = $_POST['selesai'];
$harga = $_POST['harga'];

$durasi = (new DateTime($selesai))->diff(new DateTime($mulai))->days + 1;
$total = $durasi * $harga;

$sql = "INSERT INTO transaksi (mobil_id, user_id, nama_peminjam, tanggal_mulai, tanggal_selesai, total_harga) 
        VALUES ('$mobil_id', '$user_id', '$nama', '$mulai', '$selesai', '$total')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Berhasil! Total: Rp ".number_format($total)."'); window.location='index.php';</script>";
}
?>