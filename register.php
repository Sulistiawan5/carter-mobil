<?php
session_start();
include 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek duplikasi email
    $cek = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        $error = "Email sudah terdaftar! Gunakan email lain.";
    } else {
        $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Daftar Akun - Rental Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-10">

    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Bergabunglah untuk mulai menyewa.</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 text-center border border-red-100 font-medium">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Nama Lengkap / Username</label>
                <input type="text" name="username" placeholder="Nama Anda" 
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Email Address</label>
                <input type="email" name="email" placeholder="nama@email.com" 
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Password</label>
                <input type="password" name="password" placeholder="Buat password aman" 
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all" required>
            </div>

            <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition transform active:scale-[0.98]">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="login.php" class="text-blue-600 font-bold hover:underline">Login disini</a>
        </p>
    </div>

</body>
</html>