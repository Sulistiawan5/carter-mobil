<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] != 'admin') header("Location: index.php");

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_POST['gambar']; // Input URL gambar saja biar simpel

    $query = "INSERT INTO mobil (nama_mobil, gambar, harga_per_hari) VALUES ('$nama', '$gambar', '$harga')";
    if(mysqli_query($conn, $query)) header("Location: index.php");
}
?>
<form method="POST" class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Tambah Mobil</h2>
    <input type="text" name="nama" placeholder="Nama Mobil" class="w-full border p-2 mb-2" required>
    <input type="text" name="gambar" placeholder="URL Gambar" class="w-full border p-2 mb-2" required>
    <input type="number" name="harga" placeholder="Harga/Hari" class="w-full border p-2 mb-4" required>
    <button type="submit" name="simpan" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    <a href="index.php" class="text-gray-500 ml-2">Batal</a>
</form>