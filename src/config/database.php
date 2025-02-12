<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-cum0dkt6l47c7392ta3g-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$dbname = getenv('PGSQL_DB') ?: 'alumnosueb_isvk';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'fx8YVGiALHpoM8hhEZSBTI2St6RCHCa4';  // La contraseña de la base de datos

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
