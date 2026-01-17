<?php 
session_start();
include 'koneksi.php'; 
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
$isAdmin = ($_SESSION['role'] == 'admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Carter Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-blue-700 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">🚗 RentalMobil</h1>
            <div class="flex items-center gap-4">
                <span>Halo, <b><?= $_SESSION['username']; ?></b></span>
                <a href="transaksi.php" class="text-white hover:text-yellow-300 font-medium transition">
                <?= $isAdmin ? "Data Pesanan" : "Riwayat Saya"; ?>
              </a>
                <?php if($isAdmin): ?>
                    <a href="admin_tambah.php" class="bg-green-500 px-3 py-1 rounded text-sm">+ Mobil</a>
                <?php endif; ?>
                <a href="logout.php" class="bg-red-500 px-3 py-1 rounded text-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 px-4">
        <input type="text" id="keyword" placeholder="Cari mobil..." class="w-full p-4 rounded-lg shadow-sm border focus:ring-2 focus:ring-blue-500 mb-6">
        
        <div id="container-mobil" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM mobil");
            while ($row = mysqli_fetch_assoc($query)) { include 'components/card_mobil.php'; } 
            ?>
        </div>
    </div>

    <script>
        const keyword = document.getElementById('keyword');
        const container = document.getElementById('container-mobil');
        keyword.addEventListener('keyup', () => {
            fetch('ajax_cari.php?keyword=' + keyword.value)
                .then(r => r.text()).then(d => container.innerHTML = d);
        });
    </script>
</body>
</html>