<?php
$host = getenv('MYSQL_HOST'); // Ejemplo: "localhost" o IP del servidor
$db = getenv('MYSQL_DB'); // Ejemplo: "mi_basedatos"
$user = getenv('MYSQL_USER'); // Ejemplo: "mi_usuario"
$pass = getenv('MYSQL_PASSWORD'); // Ejemplo: "mi_contraseña"

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
