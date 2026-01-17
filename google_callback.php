<?php
session_start();
include 'koneksi.php';
require_once 'config_google.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($client);
        $data = $google_service->userinfo->get();

        $email = $data->email;
        $name = $data->name;
        $google_id = $data->id;

        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $user = mysqli_fetch_assoc($check);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
        } else {
            $pass = password_hash(uniqid(), PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users (username, email, password, role, google_id) VALUES ('$name', '$email', '$pass', 'user', '$google_id')");
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $name;
            $_SESSION['role'] = 'user';
        }
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    }
}
header("Location: login.php");
?>