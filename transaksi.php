<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['role'] == 'admin');
$user_id = $_SESSION['user_id'];

// LOGIKA QUERY:
// Jika Admin -> Ambil SEMUA data
// Jika User  -> Ambil data milik DIA SAJA (berdasarkan user_id)
if ($isAdmin) {
    $query = "SELECT transaksi.*, mobil.nama_mobil, users.username 
              FROM transaksi 
              JOIN mobil ON transaksi.mobil_id = mobil.id
              JOIN users ON transaksi.user_id = users.id 
              ORDER BY transaksi.id DESC";
} else {
    $query = "SELECT transaksi.*, mobil.nama_mobil 
              FROM transaksi 
              JOIN mobil ON transaksi.mobil_id = mobil.id 
              WHERE transaksi.user_id = '$user_id' 
              ORDER BY transaksi.id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-700 text-white p-4 shadow mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">📄 Data Transaksi</h1>
            <a href="index.php" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-sm">Kembali ke Home</a>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                <?= $isAdmin ? "Semua Pesanan Masuk (Admin)" : "Riwayat Pesanan Saya"; ?>
            </h2>

            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Mobil</th>
                        <?php if($isAdmin): ?><th class="py-3 px-6 text-left">Peminjam</th><?php endif; ?>
                        <th class="py-3 px-6 text-left">Tanggal Sewa</th>
                        <th class="py-3 px-6 text-left">Total Harga</th>
                        <th class="py-3 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            // Hitung status sederhana (berdasarkan tanggal)
                            $today = date('Y-m-d');
                            $status = ($today > $row['tanggal_selesai']) ? 'Selesai' : 'Sedang Dipinjam';
                            $warna_status = ($status == 'Selesai') ? 'bg-green-200 text-green-700' : 'bg-yellow-200 text-yellow-700';
                    ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left whitespace-nowrap font-bold"><?= $no++; ?></td>
                            <td class="py-3 px-6 text-left font-medium text-gray-800"><?= $row['nama_mobil']; ?></td>
                            
                            <?php if($isAdmin): ?>
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span class="font-medium"><?= $row['nama_peminjam']; ?></span>
                                    </div>
                                </td>
                            <?php endif; ?>

                            <td class="py-3 px-6 text-left">
                                <?= date('d M Y', strtotime($row['tanggal_mulai'])); ?> 
                                <span class="text-gray-400 mx-1">s/d</span> 
                                <?= date('d M Y', strtotime($row['tanggal_selesai'])); ?>
                            </td>
                            <td class="py-3 px-6 text-left font-bold text-blue-600">
                                Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="<?= $warna_status; ?> py-1 px-3 rounded-full text-xs">
                                    <?= $status; ?>
                                </span>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400">Belum ada data transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>