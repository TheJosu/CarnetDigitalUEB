<?php
require_once 'vendor/autoload.php';

session_start();

// Configura el cliente de Google
$client = new Google_Client();
$client->setClientId('TU_CLIENT_ID');
$client->setClientSecret('TU_CLIENT_SECRET');
$client->setRedirectUri('https://carnetdigitalueb.onrender.com/src/oauth2callback');

if (isset($_GET['code'])) {
    // Intercambia el código de autorización por un token de acceso
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $token = $client->getAccessToken();

    // Guarda el token en la sesión o en una base de datos
    $_SESSION['access_token'] = $token;

    // Redirige al usuario a una página protegida o al inicio de sesión
    header('Location: /protected.php');
    exit();
} else {
    echo 'Error al obtener el código de autorización.';
}
