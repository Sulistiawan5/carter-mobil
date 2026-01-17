<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] != 'admin') header("Location: index.php");

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id='$id'"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_POST['gambar'];
    $status = $_POST['status'];

    $query = "UPDATE mobil SET nama_mobil='$nama', gambar='$gambar', harga_per_hari='$harga', status='$status' WHERE id='$id'";
    if(mysqli_query($conn, $query)) header("Location: index.php");
}
?>
<form method="POST" class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Mobil</h2>
    <input type="text" name="nama" value="<?= $data['nama_mobil'] ?>" class="w-full border p-2 mb-2" required>
    <input type="text" name="gambar" value="<?= $data['gambar'] ?>" class="w-full border p-2 mb-2" required>
    <input type="number" name="harga" value="<?= $data['harga_per_hari'] ?>" class="w-full border p-2 mb-2" required>
    
    <select name="status" class="w-full border p-2 mb-4">
        <option value="tersedia" <?= ($data['status']=='tersedia')?'selected':'' ?>>Tersedia</option>
        <option value="dipinjam" <?= ($data['status']=='dipinjam')?'selected':'' ?>>Dipinjam</option>
    </select>

    <button type="submit" name="update" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
    <a href="index.php" class="text-gray-500 ml-2">Batal</a>
</form>