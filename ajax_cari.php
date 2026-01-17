<?php
session_start();
include 'koneksi.php';
$isAdmin = ($_SESSION['role'] == 'admin'); // Wajib ada untuk card_mobil
$key = $_GET['keyword'];
$query = mysqli_query($conn, "SELECT * FROM mobil WHERE nama_mobil LIKE '%$key%'");
while ($row = mysqli_fetch_assoc($query)) { include 'components/card_mobil.php'; }
?>