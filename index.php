<?php 
session_start();
include 'koneksi.php'; 

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Cek Role Admin
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Carter Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-blue-700 text-white shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                
                <a href="index.php" class="flex items-center gap-2 group">
                    <span class="text-2xl">🚗</span>
                    <span class="text-xl font-bold tracking-tight group-hover:text-blue-200 transition">RentalMobil</span>
                </a>

                <div class="flex items-center gap-2 sm:gap-6 text-sm sm:text-base w-full md:w-auto justify-between md:justify-end">
                    
                    <a href="transaksi.php" class="flex items-center gap-1 hover:text-yellow-300 font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="hidden sm:inline"><?= $isAdmin ? "Data Pesanan" : "Riwayat Saya"; ?></span>
                        <span class="sm:hidden">Pesanan</span>
                    </a>

                    <div class="h-6 w-px bg-blue-500 mx-2"></div>

                    <div class="flex items-center gap-4">
                        <span class="hidden md:block">Halo, <b><?= htmlspecialchars($_SESSION['username']); ?></b></span>
                        
                        <?php if($isAdmin): ?>
                            <a href="admin_tambah.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full font-bold shadow-md transition transform hover:scale-105 flex items-center gap-1">
                                <span>+</span> <span class="hidden sm:inline">Mobil</span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-bold shadow-md transition transform hover:scale-105 text-sm">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10 px-4 pb-20 flex-grow">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Temukan Mobil Impianmu</h2>
            <p class="text-gray-500">Pilih mobil terbaik untuk perjalanan yang nyaman dan aman.</p>
        </div>

        <div class="relative mb-12 max-w-xl mx-auto group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-6 h-6 text-gray-400 group-focus-within:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="keyword" placeholder="Cari nama mobil (misal: Pajero)..." 
                   class="w-full pl-12 pr-4 py-4 rounded-full shadow-sm border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-gray-700 text-lg outline-none">
        </div>
        
        <div id="container-mobil" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            
            $query_str = "SELECT * FROM mobil  ORDER BY id DESC";
            
            // Fallback jika belum update database soft delete
            // $query_str = "SELECT * FROM mobil ORDER BY id DESC"; 

            $query = mysqli_query($conn, $query_str);
            
            if(mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) { 
                    include 'components/card_mobil.php'; 
                } 
            } else {
                echo '<div class="col-span-full text-center py-20 text-gray-400">
                        <p class="text-xl font-semibold">Belum ada mobil tersedia.</p>
                      </div>';
            }
            ?>
        </div>

    </div>

    <footer class="bg-white border-t mt-auto py-6">
        <div class="container mx-auto text-center text-gray-500 text-sm">
            &copy; <?= date('Y'); ?> RentalMobil-Ku.
        </div>
    </footer>

    <script>
        const keyword = document.getElementById('keyword');
        const container = document.getElementById('container-mobil');

        keyword.addEventListener('keyup', function() {
            
             container.style.opacity = '0.5';
            
            fetch('ajax_cari.php?keyword=' + keyword.value)
                .then(response => response.text())
                .then(data => {
                    container.innerHTML = data;
                     container.style.opacity = '1';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

</body>
</html>