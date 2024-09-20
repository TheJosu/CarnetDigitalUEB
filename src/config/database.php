<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-crmtr8jqf0us7387guag-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$dbname = getenv('PGSQL_DB') ?: 'alumnosueb_at6d';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'aZR5wGtCuRg98I1SuLrVpL6gPv9ogBhc';  // La contraseña de la base de datos

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
