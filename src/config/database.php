<?php
$host = getenv('PG_HOST');
$db = getenv('PG_DB');
$user = getenv('PG_USER');
$pass = getenv('PG_PASSWORD');

try {
    $conn = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
