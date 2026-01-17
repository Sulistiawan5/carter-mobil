<?php
require_once 'vendor/autoload.php';

// Load file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$clientID = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
$redirectUri = $_ENV['GOOGLE_REDIRECT_URI'];

// Setup Google 
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$google_login_url = $client->createAuthUrl();
?>