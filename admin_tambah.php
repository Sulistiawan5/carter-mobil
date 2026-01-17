<?php
session_start();
include 'koneksi.php';

// Cek Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    
    // Logika Upload Gambar
    $gambar = '';

    // 1. Cek apakah user mengupload file?
    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        
        // Buat nama unik agar tidak bentrok (misal: 170922_avanza.jpg)
        $nama_baru = time() . "_" . $nama_file;
        $path_upload = "uploads/" . $nama_baru;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($tmp_file, $path_upload)) {
            $gambar = $path_upload; // Simpan path lokal ke database
        } else {
            $error = "Gagal mengupload gambar!";
        }
    } 
    // 2. Jika tidak upload file, cek apakah ada URL?
    else if (!empty($_POST['gambar_url'])) {
        $gambar = $_POST['gambar_url'];
    }

    // Simpan ke Database jika gambar ada
    if ($gambar != '') {
        $query = "INSERT INTO mobil (nama_mobil, gambar, harga_per_hari) VALUES ('$nama', '$gambar', '$harga')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Harap masukkan URL gambar atau Upload foto!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <form method="POST" enctype="multipart/form-data" class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Mobil Baru</h2>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Mobil</label>
            <input type="text" name="nama" placeholder="Contoh: Toyota Avanza" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Gambar Mobil</label>
            
            <div class="flex flex-col gap-3">
                <input type="text" name="gambar_url" placeholder="Opsi 1: Paste URL Gambar (https://...)" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <div class="text-center text-gray-400 text-sm font-bold">- ATAU -</div>

                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> (Opsi 2)</p>
                            <p class="text-xs text-gray-500">PNG, JPG, or JPEG</p>
                        </div>
                        <input id="dropzone-file" type="file" name="gambar_file" class="hidden" accept="image/*" />
                    </label>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Harga Sewa (Per Hari)</label>
            <input type="number" name="harga" placeholder="Contoh: 300000" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
            <button type="submit" name="simpan" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg transform transition hover:scale-105">
                Simpan Data
            </button>
        </div>
    </form>

</body>
</html>