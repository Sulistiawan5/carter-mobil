<?php
session_start(); include 'koneksi.php';
$id = $_GET['id'];
$mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id='$id'"));
?>
<!DOCTYPE html>
<html lang="id">
<head> <script src="https://cdn.tailwindcss.com"></script> </head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="process_booking.php" method="POST" class="bg-white p-8 rounded shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Sewa: <?= $mobil['nama_mobil']; ?></h2>
        <input type="hidden" name="mobil_id" value="<?= $mobil['id']; ?>">
        <input type="hidden" name="harga" value="<?= $mobil['harga_per_hari']; ?>">
        
        <label class="block text-sm font-bold mb-1">Mulai</label>
        <input type="date" name="mulai" class="w-full border p-2 mb-3 rounded" required>
        
        <label class="block text-sm font-bold mb-1">Selesai</label>
        <input type="date" name="selesai" class="w-full border p-2 mb-4 rounded" required>
        
        <div class="flex gap-2">
            <a href="index.php" class="flex-1 text-center border py-2 rounded">Batal</a>
            <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded">Konfirmasi</button>
        </div>
    </form>
</body>
</html>