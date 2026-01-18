<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
$user_id = $_SESSION['user_id'];

// Logika Query
if ($isAdmin) {
    $query = "SELECT transaksi.*, mobil.nama_mobil, users.username, users.email 
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

// Simpan data ke array agar bisa diloop 2 kali (untuk tampilan HP & Laptop)
$data_transaksi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data_transaksi[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen pb-20">

    <nav class="bg-blue-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold flex items-center gap-2">
                Data Transaksi
            </h1>
            <a href="index.php" class="bg-blue-600 hover:bg-blue-500 border border-blue-500 px-3 py-1.5 rounded-lg text-sm transition">
                &larr; Kembali
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-6">
        <h2 class="text-xl font-bold mb-6 text-gray-800 border-l-4 border-blue-600 pl-3">
            <?= $isAdmin ? "Semua Pesanan Masuk" : "Riwayat Pesanan Saya"; ?>
        </h2>

        <div class="grid grid-cols-1 gap-4 md:hidden">
            <?php if (count($data_transaksi) > 0): ?>
                <?php foreach ($data_transaksi as $row): 
                    $today = date('Y-m-d');
                    $is_selesai = ($today > $row['tanggal_selesai']);
                    $status_label = $is_selesai ? 'Selesai' : 'Sedang Dipinjam';
                    $status_class = $is_selesai ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
                    $durasi = (new DateTime($row['tanggal_selesai']))->diff(new DateTime($row['tanggal_mulai']))->days + 1;
                ?>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-gray-400">Tanggal Sewa</p>
                            <p class="text-sm font-semibold text-gray-700">
                                <?= date('d M', strtotime($row['tanggal_mulai'])); ?> - <?= date('d M Y', strtotime($row['tanggal_selesai'])); ?>
                            </p>
                        </div>
                        <span class="<?= $status_class; ?> px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                            <?= $status_label; ?>
                        </span>
                    </div>

                    <hr class="border-dashed border-gray-200 my-3">

                    <div class="mb-4">
                        <p class="text-xs text-gray-400 mb-1">Unit Mobil</p>
                        <h3 class="text-lg font-bold text-gray-800"><?= $row['nama_mobil']; ?></h3>
                        <?php if($isAdmin): ?>
                             <p class="text-xs text-gray-500 mt-1">Penyewa: <span class="text-blue-600"><?= $row['username']; ?></span></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                        <span class="text-xs text-gray-500">Total Biaya (<?= $durasi; ?> Hari)</span>
                        <span class="text-blue-600 font-bold text-lg">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="bg-white p-8 text-center rounded-xl shadow-sm">
                    <p class="text-gray-400">Belum ada riwayat transaksi.</p>
                </div>
            <?php endif; ?>
        </div>


        <div class="hidden md:block bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">No</th>
                        <th class="p-4 font-semibold">Unit Mobil</th>
                        <?php if($isAdmin): ?><th class="p-4 font-semibold">Penyewa</th><?php endif; ?>
                        <th class="p-4 font-semibold">Tanggal Sewa</th>
                        <th class="p-4 font-semibold">Total Biaya</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    <?php 
                    $no = 1;
                    if(count($data_transaksi) > 0):
                        foreach ($data_transaksi as $row): 
                            $today = date('Y-m-d');
                            $is_selesai = ($today > $row['tanggal_selesai']);
                            $status_label = $is_selesai ? 'Selesai' : 'Aktif';
                            $status_color = $is_selesai ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
                    ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-gray-400"><?= $no++; ?></td>
                            <td class="p-4 font-bold text-gray-800"><?= $row['nama_mobil']; ?></td>
                            <?php if($isAdmin): ?>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium"><?= $row['username']; ?></span>
                                        <span class="text-xs text-gray-400"><?= $row['email']; ?></span>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <td class="p-4">
                                <?= date('d M', strtotime($row['tanggal_mulai'])); ?> - <?= date('d M Y', strtotime($row['tanggal_selesai'])); ?>
                            </td>
                            <td class="p-4 font-bold text-blue-600">
                                Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="p-4 text-center">
                                <span class="<?= $status_color; ?> px-2.5 py-1 rounded-full text-xs font-bold">
                                    <?= $status_label; ?>
                                </span>
                            </td>
                        </tr>
                    <?php 
                        endforeach; 
                    else:
                    ?>
                        <tr><td colspan="6" class="p-8 text-center text-gray-400">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>