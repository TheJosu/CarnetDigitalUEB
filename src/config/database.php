<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-d6gfcmua2pns73ft293g-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$dbname = getenv('PGSQL_DB') ?: 'alumnosueb_t1qc';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'OzKHrB6Ovi0o9YljuP2V7rzAmtizby7p';  // La contraseña de la base de datos

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
