<?php
session_start();
include 'koneksi.php';

// Jika sudah login, lempar ke index
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek apakah email sudah ada
    $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $error = "Email sudah terdaftar! Silakan login.";
    } else {
        // Masukkan data baru
        $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Registrasi Berhasil! Silakan Login.');
                    window.location.href='login.php';
                  </script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Daftar Akun</h2>
        
        <?php if(isset($error)) echo "<p class='bg-red-100 text-red-500 text-sm p-2 mb-4 rounded text-center'>$error</p>"; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">Username</label>
                <input type="text" name="username" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="login.php" class="text-blue-600 font-bold hover:underline">Login disini</a>
        </p>
    </div>

</body>
</html>