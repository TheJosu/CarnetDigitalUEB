<?php
$host = getenv('PGSQL_HOST');
$dbname = getenv('PGSQL_DB');
$username = getenv('PGSQL_USER');
$password = getenv('PGSQL_PASSWORD');

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
