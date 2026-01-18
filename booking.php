<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

//Cek ID Mobil di URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_mobil = $_GET['id'];

//Ambil Data Mobil
$query = mysqli_query($conn, "SELECT * FROM mobil WHERE id = '$id_mobil'");
$mobil = mysqli_fetch_assoc($query);

// Jika mobil tidak ditemukan 
if (!$mobil) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa <?= $mobil['nama_mobil']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen pb-10">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="font-bold text-gray-800 text-lg">Form Penyewaan</h1>
            <a href="index.php" class="text-sm font-medium text-gray-500 hover:text-blue-600 flex items-center gap-1">
                ✕ Batal
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mt-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">

            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <img src="<?= $mobil['gambar']; ?>" alt="<?= $mobil['nama_mobil']; ?>" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1"><?= $mobil['nama_mobil']; ?></h2>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">Unit Ready</span>
                            <span class="text-gray-500 text-sm">Tahun 2023</span>
                        </div>
                        <hr class="border-gray-100 my-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Harga Sewa</span>
                            <span class="text-xl font-bold text-blue-600">Rp <?= number_format($mobil['harga_per_hari'], 0, ',', '.'); ?> <span class="text-sm text-gray-400 font-normal">/hari</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-sm text-blue-800">
                    <p class="font-bold mb-1">Info Penting:</p>
                    <ul class="list-disc list-inside opacity-80">
                        <li>Pastikan tanggal sewa sudah benar.</li>
                        <li>Denda keterlambatan berlaku.</li>
                        <li>Mobil diambil di kantor pusat.</li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Lengkapi Detail Sewa</h3>

                    <form action="process_booking.php" method="POST" id="bookingForm">
                        <input type="hidden" name="mobil_id" value="<?= $mobil['id']; ?>">
                        <input type="hidden" name="harga" id="harga_per_hari" value="<?= $mobil['harga_per_hari']; ?>">

                        <div class="mb-4">
                            <label class="block text-gray-500 text-xs font-bold mb-1 uppercase">Penyewa</label>
                            <input type="text" value="<?= $_SESSION['username']; ?>" readonly 
                                   class="w-full bg-gray-100 border border-gray-200 text-gray-600 rounded-lg px-4 py-3 focus:outline-none cursor-not-allowed">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                                <input type="date" name="mulai" id="tgl_mulai" min="<?= date('Y-m-d'); ?>" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                                <input type="date" name="selesai" id="tgl_selesai" min="<?= date('Y-m-d'); ?>" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 mb-6">
                            <div class="flex justify-between mb-2 text-sm">
                                <span class="text-gray-500">Durasi Sewa</span>
                                <span class="font-medium text-gray-800" id="text_durasi">0 Hari</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">Total Biaya</span>
                                <span class="text-2xl font-bold text-blue-600" id="text_total">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-95">
                            Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const inputMulai = document.getElementById('tgl_mulai');
        const inputSelesai = document.getElementById('tgl_selesai');
        const textDurasi = document.getElementById('text_durasi');
        const textTotal = document.getElementById('text_total');
        const hargaPerHari = <?= $mobil['harga_per_hari']; ?>;

        function hitungHarga() {
            if (inputMulai.value && inputSelesai.value) {
                const start = new Date(inputMulai.value);
                const end = new Date(inputSelesai.value);

                // Validasi: Tanggal selesai tidak boleh sebelum tanggal mulai
                if (end < start) {
                    alert("Tanggal selesai tidak boleh sebelum tanggal mulai!");
                    inputSelesai.value = ""; // Reset tanggal selesai
                    textDurasi.innerText = "0 Hari";
                    textTotal.innerText = "Rp 0";
                    return;
                }

                // Hitung selisih hari
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 agar minimal 1 hari

                const totalHarga = diffDays * hargaPerHari;

                // Update Tampilan
                textDurasi.innerText = diffDays + " Hari";
                
                // Format Rupiah
                textTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(totalHarga);
            }
        }

        inputMulai.addEventListener('change', hitungHarga);
        inputSelesai.addEventListener('change', hitungHarga);
    </script>

</body>
</html>