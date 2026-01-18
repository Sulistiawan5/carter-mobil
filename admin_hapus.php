<?php
session_start();
include 'koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    
    $query = "UPDATE mobil SET is_active = 0 WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Mobil berhasil dihapus! (Data aman di database untuk riwayat transaksi)');
                window.location.href='index.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}
?>