<?php
session_start();
include 'koneksi.php';
require_once 'config_google.php'; 

// Redirect jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username']; 
            $_SESSION['role'] = $row['role'];
            header("Location: index.php");
            exit;
        }
    }
    $error = "Email atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Login - Rental Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-10">

    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700 mb-2">Selamat Datang</h1>
            <p class="text-gray-500 text-sm">Silakan login untuk mulai menyewa.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 text-center border border-red-100 font-medium">
                <?= $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Email Address</label>
                <input type="email" name="email" placeholder="contoh@email.com" 
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all" required>
            </div>

            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition transform active:scale-[0.98]">
                Masuk Sekarang
            </button>
        </form>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
            <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400 font-medium">atau</span></div>
        </div>

        <a href="<?= $google_login_url; ?>" class="flex items-center justify-center w-full border border-gray-200 py-3 rounded-lg hover:bg-gray-50 transition gap-3 font-medium text-gray-700 bg-white shadow-sm">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google"> 
            Masuk dengan Google
        </a>

        <p class="mt-8 text-center text-sm text-gray-600">
            Belum punya akun? <a href="register.php" class="text-blue-600 font-bold hover:underline">Daftar disini</a>
        </p>
    </div>

</body>
</html>