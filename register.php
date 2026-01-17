<?php
include 'koneksi.php';
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if(mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')")){
        header("Location: login.php");
    } else {
        $error = "Email sudah terdaftar!";
    }
}
?>