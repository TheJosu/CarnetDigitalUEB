<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-cs48dnl2ng1s739fps90-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$dbname = getenv('PGSQL_DB') ?: 'alumnosueb_m41q';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'Tdx2sdY4Vql3Gha15Kvseqn6W2inH9Wh';  // La contraseña de la base de datos

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
