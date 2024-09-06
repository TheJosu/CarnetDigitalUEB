<?php
// Incluir el autoload de Composer
require 'vendor/autoload.php';

// Configuración de Google Client
use Google\Client as GoogleClient;

// Configurar Google Client
$client = new GoogleClient();
$client->setAuthConfig('path/to/credentials.json');
$client->addScope(Google_Service_Drive::DRIVE_FILE);

// Obtener el token de acceso
$service = new Google_Service_Drive($client);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    // Conectar a la base de datos
    include 'config/database.php';
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Configurar cliente de Google Drive
    $client = new Client();
    $client->setAuthConfig('path/to/credentials.json'); // Ruta al archivo JSON de credenciales
    $client->addScope(Drive::DRIVE_FILE);
    $service = new Drive($client);

    if ($tipo === 'estudiante') {
        $id_cedula = $_POST['id_cedula'];
        $nombre_estudiante = $_POST['nombre_estudiante'];
        $rol = $_POST['rol'];
        $celular = $_POST['celular'];
        $correo_institucional = $_POST['correo_institucional'];
        $id_facultad = $_POST['id_facultad'];
        $id_carrera = $_POST['id_carrera'];
        $id_periodo = $_POST['id_periodo'];
        $id_ciclo = $_POST['id_ciclo'];
        $modalidad = $_POST['modalidad'];

        // Procesamiento de la fotografía
        $fotografia = $_FILES['fotografia']['name'];
        $fotoPath = '';

        if ($fotografia) {
            $target_file = $_FILES['fotografia']['tmp_name'];

            // Subir archivo a Google Drive
            $fileMetadata = new DriveFile([
                'name' => basename($fotografia),
                'mimeType' => $_FILES['fotografia']['type']
            ]);
            $content = file_get_contents($target_file);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $_FILES['fotografia']['type'],
                'uploadType' => 'multipart'
            ]);

            // Obtener el ID del archivo subido y construir la URL
            $fileId = $file->id;
            $fotoPath = "https://drive.google.com/uc?id=$fileId";
        }

        // Insertar datos en la base de datos
        $sql = "INSERT INTO Estudiante (id_cedula, nombre_estudiante, rol, celular, correo_institucional, id_facultad, id_carrera, id_periodo, id_ciclo, modalidad, fotografia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssiiiss", $id_cedula, $nombre_estudiante, $rol, $celular, $correo_institucional, $id_facultad, $id_carrera, $id_periodo, $id_ciclo, $modalidad, $fotoPath);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("Location: formulario.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
