<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-crcip9rv2p9s73cgisig-a.oregon-postgres.render.com';  // Host que Render te proporcionó
$db = getenv('PGSQL_DB') ?: 'alumnosueb';  // El nombre de la base de datos
$user = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$pass = getenv('PGSQL_PASSWORD') ?: 'YMoYLuvhnPNHv7ckBBsUQswAlG8an3Fq';  // La contraseña de la base de datos

try {
    // Conectar usando la cadena de conexión a PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar un mensaje de error si la conexión falla
    echo "Connection failed: " . $e->getMessage();
}
?>
