<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] == 'admin' && isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM mobil WHERE id='$id'");
}
header("Location: index.php");
?>