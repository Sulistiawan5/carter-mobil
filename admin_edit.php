<?php
session_start();
include 'koneksi.php';

// Cek Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil Data Lama
$query = mysqli_query($conn, "SELECT * FROM mobil WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

// Proses Update
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    
    // Default: Gunakan gambar lama
    $gambar_final = $data['gambar'];

    // 1. Cek Upload File Baru
    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        $nama_baru = time() . "_" . $nama_file;
        $path_upload = "uploads/" . $nama_baru;

        if (move_uploaded_file($tmp_file, $path_upload)) {
            $gambar_final = $path_upload;
        }
    } 
    // 2. Cek URL Baru
    else if (!empty($_POST['gambar_url'])) {
        $gambar_final = $_POST['gambar_url'];
    }

    $query_update = "UPDATE mobil SET 
                     nama_mobil = '$nama', 
                     harga_per_hari = '$harga', 
                     gambar = '$gambar_final' 
                     WHERE id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <form method="POST" enctype="multipart/form-data" class="w-full max-w-lg bg-white p-8 rounded-xl shadow-xl">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Edit Data Mobil</h2>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm font-medium"><?= $error; ?></div>
        <?php endif; ?>

        <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2">Nama Mobil</label>
            <input type="text" name="nama" value="<?= $data['nama_mobil']; ?>" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2">Gambar Mobil</label>
            
            <div class="mb-4 p-4 border rounded-lg bg-gray-50 flex flex-col items-center justify-center text-center">
                <img id="preview-img" src="<?= $data['gambar']; ?>" 
                     class="w-full h-48 object-cover rounded-lg shadow-sm mb-2 border border-gray-200">
                
                <p id="preview-text" class="text-xs text-gray-500 font-medium">Gambar Saat Ini (Database)</p>
            </div>

            <div class="flex flex-col gap-3">
                <input type="text" id="input-url" name="gambar_url" placeholder="Ganti dengan URL baru..." class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                
                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-bold">ATAU GANTI FILE</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <label for="input-file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <p class="text-sm text-gray-500"><span class="font-semibold text-blue-600">Klik Ganti Foto</span> (Opsi 2)</p>
                    </div>
                    <input id="input-file" type="file" name="gambar_file" class="hidden" accept="image/*" />
                </label>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 font-bold mb-2">Harga Sewa (Per Hari)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rp</span>
                <input type="number" name="harga" value="<?= $data['harga_per_hari']; ?>" class="w-full border border-gray-300 p-3 pl-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="index.php" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition">Batal</a>
            <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg transform transition hover:scale-105">
                Update Data
            </button>
        </div>
    </form>

    <script>
        const inputFile = document.getElementById('input-file');
        const inputUrl = document.getElementById('input-url');
        const previewImg = document.getElementById('preview-img');
        const previewText = document.getElementById('preview-text');
        
        // PENTING: Simpan gambar asli dari PHP ke variabel JS
        const originalSrc = "<?= $data['gambar']; ?>"; 

        // 1. Jika User Upload File
        inputFile.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewText.textContent = "Preview: File Baru yang akan diupload";
                    previewText.className = "text-xs text-green-600 font-bold mt-2";
                    inputUrl.value = ""; // Kosongkan URL
                }
                reader.readAsDataURL(file);
            }
        });

        // 2. Jika User Ketik URL
        inputUrl.addEventListener('input', function() {
            const url = this.value;
            if (url) {
                previewImg.src = url;
                previewText.textContent = "Preview: URL Baru";
                previewText.className = "text-xs text-blue-600 font-bold mt-2";
                inputFile.value = ""; // Reset input file
            } else {
                // Jika URL dihapus, KEMBALI KE GAMBAR ASLI DATABASE
                previewImg.src = originalSrc;
                previewText.textContent = "Gambar Saat Ini (Database)";
                previewText.className = "text-xs text-gray-500 font-medium mt-2";
            }
        });
    </script>

</body>
</html>