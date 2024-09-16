<?php
$host = getenv('PGSQL_HOST') ?: 'dpg-crhhb8g8fa8c7392m6o0-a.oregon-postgres.render.com';  // Host que Render te proporcionó         
$dbname = getenv('PGSQL_DB') ?: 'prueba_bgqe';  // El nombre de la base de datos
$username = getenv('PGSQL_USER') ?: 'root';  // El usuario de la base de datos
$password = getenv('PGSQL_PASSWORD') ?: 'VN0adU5Mx2ob7bsPJl1YA6EXsshxat3P';  // La contraseña de la base de datos.

try {
    // Conectar usando la cadena de conexión a PostgreSQL
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar un mensaje de error si la conexión falla
    echo "Connection failed: " . $e->getMessage();
}
?>
