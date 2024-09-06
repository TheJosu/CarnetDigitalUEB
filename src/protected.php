<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('TU_CLIENT_ID');
$client->setClientSecret('TU_CLIENT_SECRET');
$client->setRedirectUri('https://carnetdigitalueb.onrender.com/src/oauth2callback');

if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // Muestra la información del usuario
    echo 'Nombre: ' . htmlspecialchars($userInfo->name);
    echo 'Email: ' . htmlspecialchars($userInfo->email);
} else {
    echo 'No estás autenticado. <a href="auth.php">Inicia sesión</a>';
}
