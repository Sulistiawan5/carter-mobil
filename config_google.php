<?php
require_once 'vendor/autoload.php';

// GANTI DENGAN KREDENSIAL ANDA
$clientID = 'MASUKKAN_CLIENT_ID_GOOGLE_DISINI';
$clientSecret = 'MASUKKAN_CLIENT_SECRET_GOOGLE_DISINI';
$redirectUri = 'http://localhost/carter-mobil/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$google_login_url = $client->createAuthUrl();
?>