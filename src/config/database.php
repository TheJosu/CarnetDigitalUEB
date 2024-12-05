<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-ct8tq9m8ii6s73cge8m0-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$dbname = getenv('PGSQL_DB') ?: 'alumnosueb_ob2o';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'sPYk1tUFuXRcShPrSZ8Gd6dUp7F2DwC3';  // La contraseña de la base de datos

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
