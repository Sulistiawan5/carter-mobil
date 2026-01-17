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

// Jika tombol Update ditekan
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    
    // Default: Gunakan gambar lama
    $gambar_final = $data['gambar'];

    // LOGIKA GANTI GAMBAR
    // 1. Cek apakah ada file baru diupload?
    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        $nama_baru = time() . "_" . $nama_file;
        $path_upload = "uploads/" . $nama_baru;

        if (move_uploaded_file($tmp_file, $path_upload)) {
            $gambar_final = $path_upload;
            
            // (Opsional) Hapus file lama jika ada di folder uploads agar hemat storage
            // if (file_exists($data['gambar']) && strpos($data['gambar'], 'uploads/') !== false) {
            //     unlink($data['gambar']);
            // }
        }
    } 
    // 2. Jika tidak upload file, cek apakah user input URL baru?
    else if (!empty($_POST['gambar_url'])) {
        $gambar_final = $_POST['gambar_url'];
    }

    // Update Database
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <form method="POST" enctype="multipart/form-data" class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg my-10">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Edit Data Mobil</h2>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm"><?= $error; ?></div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Mobil</label>
            <input type="text" name="nama" value="<?= $data['nama_mobil']; ?>" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Gambar Mobil</label>
            
            <div class="mb-3 p-2 border rounded bg-gray-50 flex items-center gap-4">
                <img src="<?= $data['gambar']; ?>" class="w-20 h-20 object-cover rounded shadow">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Gambar Saat Ini</p>
                    <p class="text-xs text-gray-400">Biarkan input kosong jika tidak ingin mengubah gambar.</p>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <input type="text" name="gambar_url" placeholder="Ganti dengan URL baru..." class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <div class="text-center text-gray-400 text-sm font-bold">- ATAU UPLOAD BARU -</div>

                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="text-sm text-gray-500"><span class="font-semibold">Klik ganti foto</span> (Opsi 2)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="gambar_file" class="hidden" accept="image/*" />
                </label>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Harga Sewa (Per Hari)</label>
            <input type="number" name="harga" value="<?= $data['harga_per_hari']; ?>" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
            <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded shadow-lg transform transition hover:scale-105">
                Update Data
            </button>
        </div>
    </form>

</body>
</html>