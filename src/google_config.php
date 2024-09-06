<?php
require_once 'vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

session_start();

$client = new Google_Client();
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
$client->addScope(Google_Service_Drive::DRIVE_FILE);
