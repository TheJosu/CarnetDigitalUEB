<?php
require_once 'google_config.php';

if (!isset($_SESSION['access_token'])) {
    header('Location: /auth.php');
    exit();
}

$client->setAccessToken($_SESSION['access_token']);
$service = new Google_Service_Drive($client);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = new Google_Service_Drive_DriveFile();
    $file->setName($_FILES['file']['name']);
    $file->setMimeType($_FILES['file']['type']);

    $result = $service->files->create(
        $file,
        array(
            'data' => file_get_contents($_FILES['file']['tmp_name']),
            'mimeType' => $_FILES['file']['type'],
            'uploadType' => 'multipart'
        )
    );

    echo 'Archivo cargado correctamente: ' . $result->id;
}
