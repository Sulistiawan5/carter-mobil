<?php
session_start();
include 'koneksi.php';
require_once 'config_google.php';

if (isset($_SESSION['login'])) { header("Location: index.php"); exit; }

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: index.php"); exit;
        }
    }
    $error = "Email atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Carter Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>
        <?php if(isset($error)) echo "<p class='text-red-500 text-sm mb-2'>$error</p>"; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-3 rounded" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-4 rounded" required>
            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded">Masuk</button>
        </form>

        <div class="flex items-center justify-between my-4">
            <hr class="w-full border-gray-300"> <span class="px-2 text-gray-500 text-sm">ATAU</span> <hr class="w-full border-gray-300">
        </div>

        <a href="<?= $google_login_url; ?>" class="flex items-center justify-center border border-gray-300 py-2 rounded hover:bg-gray-50">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-2"> Login Google
        </a>

        <p class="mt-4 text-center text-sm">Belum punya akun? <a href="register.php" class="text-blue-600">Daftar</a></p>
    </div>
</body>
</html>