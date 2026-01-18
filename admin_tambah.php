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
    $gambar = '';

    // 1. Cek Upload File
    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        $nama_baru = time() . "_" . $nama_file;
        $path_upload = "uploads/" . $nama_baru;

        if (move_uploaded_file($tmp_file, $path_upload)) {
            $gambar = $path_upload;
        }
    } 
    // 2. Cek URL
    else if (!empty($_POST['gambar_url'])) {
        $gambar = $_POST['gambar_url'];
    }

    // Simpan ke Database
    if ($gambar != '') {
        $query = "INSERT INTO mobil (nama_mobil, gambar, harga_per_hari, is_active) VALUES ('$nama', '$gambar', '$harga', 1)";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Mobil berhasil ditambahkan!'); window.location.href='index.php';</script>";
        } else {
            $error = "Error Database: " . mysqli_error($conn);
        }
    } else {
        $error = "Wajib masukkan URL gambar atau Upload foto!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mobil Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <form method="POST" enctype="multipart/form-data" class="w-full max-w-lg bg-white p-8 rounded-xl shadow-xl">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Tambah Mobil Baru</h2>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm font-medium">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2">Nama Mobil</label>
            <input type="text" name="nama" placeholder="Contoh: Honda Civic Turbo" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2">Gambar Mobil</label>
            
            <div class="mb-4 p-4 border rounded-lg bg-gray-50 flex flex-col items-center justify-center text-center">
                <img id="preview-img" src="https://via.placeholder.com/400x200?text=Preview+Akan+Muncul+Disini" 
                     class="w-full h-48 object-cover rounded-lg shadow-sm mb-2 border border-gray-200">
                <p id="preview-text" class="text-xs text-gray-400">Belum ada gambar yang dipilih</p>
            </div>

            <div class="flex flex-col gap-3">
                <input type="text" id="input-url" name="gambar_url" placeholder="Opsi 1: Paste Link Gambar (https://...)" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                
                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-bold">ATAU UPLOAD</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <label for="input-file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-gray-500"><span class="font-semibold text-blue-600">Klik Upload</span> (Opsi 2)</p>
                    </div>
                    <input id="input-file" type="file" name="gambar_file" class="hidden" accept="image/*" />
                </label>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 font-bold mb-2">Harga Sewa (Per Hari)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rp</span>
                <input type="number" name="harga" placeholder="0" class="w-full border border-gray-300 p-3 pl-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="index.php" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition">Batal</a>
            <button type="submit" name="simpan" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg transform transition hover:scale-105">
                Simpan Mobil
            </button>
        </div>
    </form>

    <script>
        const inputFile = document.getElementById('input-file');
        const inputUrl = document.getElementById('input-url');
        const previewImg = document.getElementById('preview-img');
        const previewText = document.getElementById('preview-text');
        
        // Placeholder default (Gambar abu-abu)
        const defaultSrc = "https://via.placeholder.com/400x200?text=Preview+Akan+Muncul+Disini";

        // 1. Jika Upload File
        inputFile.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewText.textContent = "Preview dari File Upload";
                    previewText.className = "text-xs text-green-600 font-bold mt-2";
                    inputUrl.value = ""; // Kosongkan URL
                }
                reader.readAsDataURL(file);
            }
        });

        // 2. Jika Input URL
        inputUrl.addEventListener('input', function() {
            const url = this.value;
            if (url) {
                previewImg.src = url;
                previewText.textContent = "Preview dari URL";
                previewText.className = "text-xs text-blue-600 font-bold mt-2";
                inputFile.value = ""; // Reset file input
            } else {
                previewImg.src = defaultSrc;
                previewText.textContent = "Belum ada gambar yang dipilih";
                previewText.className = "text-xs text-gray-400 mt-2";
            }
        });
    </script>

</body>
</html>